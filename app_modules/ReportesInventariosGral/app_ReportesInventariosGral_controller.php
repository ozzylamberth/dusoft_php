<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_ReportesInventariosGral_controller.php,v 1.1 2010/04/09 19:50:04 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  /**
  * Clase Control: ReportesInventariosGral
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Hugo F  Manrique
  */
  class app_ReportesInventariosGral_controller extends classModulo
  {
    /**
    * Constructor de la clase
    */
    function app_ReportesInventariosGral_controller(){}
    /**
    * Funcion principal del modulo
    *
    * @return boolean
    */
    function main()
    {
      $cls = AutoCarga::factory("ListaReportes","classes","app","ReportesInventariosGral");
      $permisos = $cls->ObtenerPermisos(UserGetUID());
      
			$titulo[0] = 'EMPRESAS';
			$titulo[1] = 'CENTRO UTILIDAD';
			$titulo[2] = 'BODEGAS';
			$url[0] = 'app';										//contenedor 
			$url[1] = 'ReportesInventariosGral';	  //módulo 
			$url[2] = 'controller';							//clase 
			$url[3] = 'Menu';				            //método 
			$url[4] = 'reportes';					//indice del request
			$this->salida .= gui_theme_menu_acceso('MODULO DE REPORTES',$titulo,$permisos,$url,ModuloGetURL('system','Menu'));

      return true;
    }
    /**
    * Funcion de control, para la creacion del menu
    *
    * @return boolean
    */
    function Menu()
    {
      $request = $_REQUEST;
      if($request['reportes'])
        SessionSetVar("PermisosReportesGral",$request['reportes']);
      
      $action['volver'] = ModuloGetURL('app','ReportesInventariosGral','controller','main');
      $action['proveedores'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesProveedores');
      $action['conformes'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesProveedoresNoConformes');
      $action['movimiento'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesProductosMovimiento');
      $action['vencimiento'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesProductosVencimiento');
      $action['codigobarras'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesUsuariosCBarras');
      $action['estadopacientes'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesEstadosPacientes');
      $action['pendientesdespacho'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesPendientesDespachar');
      $action['pendientescompras'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesPendientesCompras');
      $action['logauditoria'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesLogAuditoria');
      $action['actastecnicas'] = ModuloGetURL('app','ReportesInventariosGral','controller','ActasTecnicas');
      $action['selectivo'] = ModuloGetURL('app','ReportesInventariosGral','controller','ConteoDiarioProductos');
      $action['despachos_ingresos'] = ModuloGetURL('app','ReportesInventariosGral','controller','DespachosIngresados');
      $mdl = AutoCarga::factory("MensajesModuloHTML","views","app","ReportesInventariosGral");
    
      $this->salida .= $mdl->FormaMenuInicial($action);
      return true;
    }
    /**
    * Funcion de control
    *
    * @return boolean
    */
    function ListadoReportesProveedores()
    {
      $request = $_REQUEST;
      $empresa = SessionGetVar("PermisosReportesGral");
            
      $mdl = AutoCarga::factory("ReportesHTML","views","app","ReportesInventariosGral");
      $cls = AutoCarga::factory("ListaReportes","classes","app","ReportesInventariosGral");

      $tipos_terceros = $cls->ObtenerTipoIdTerceros();
      
      $datosN = array();
      if($request['buscador'])
      {
        $datosN = $cls->ObtenerListadoProveedores($empresa['empresa'],$request['buscador'],$request['offset']);
        $request['buscador']['usuario_id'] = UserGetUID();
        $request['buscador']['empresa_id'] = $empresa['empresa'];
      }
      $action['volver'] = ModuloGetURL('app','ReportesInventariosGral','controller','Menu');
      $action['buscar'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesProveedores');
      $action['paginador'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesProveedores',array("buscador"=>$request['buscador']));
      
      $this->salida .= $mdl->FormaBuscarProveedor($action,$request['buscador'],$tipos_terceros,$datosN, $cls->conteo, $cls->pagina);
      return true;
    }
    /**
    * Funcion de control 
    *
    * @return boolean
    */
    function ListadoReportesProveedoresNoConformes()
    {
      $request = $_REQUEST;
      $empresa = SessionGetVar("PermisosReportesGral");
            
      $mdl = AutoCarga::factory("ReportesHTML","views","app","ReportesInventariosGral");
      $cls = AutoCarga::factory("ListaReportes","classes","app","ReportesInventariosGral");
            
      $tipos_terceros = $cls->ObtenerTipoIdTerceros();
      
      $datosN = array();
      if($request['buscador'])
      {
        $datosN = $cls->ObtenerListadoProveedoresNoConforme($empresa['empresa'],$request['buscador'],$request['offset']);
        $request['buscador']['usuario_id'] = UserGetUID();
        $request['buscador']['empresa_id'] = $empresa['empresa'];
      }
      $action['volver'] = ModuloGetURL('app','ReportesInventariosGral','controller','Menu');
      $action['buscar'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesProveedoresNoConformes');
      $action['paginador'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesProveedoresNoConformes',array("buscador"=>$request['buscador']));
      
      $this->salida .= $mdl->FormaBuscarProveedorNoConforme($action,$request['buscador'],$tipos_terceros,$datosN, $cls->conteo, $cls->pagina);
      return true;
    }        
    /**
    * Funcion de control 
    *
    * @return boolean
    */
    function ListadoReportesProductosMovimiento()
    {
      $request = $_REQUEST;
      $empresa = SessionGetVar("PermisosReportesGral");
            
      $mdl = AutoCarga::factory("ReportesHTML","views","app","ReportesInventariosGral");
      $cls = AutoCarga::factory("ListaReportes","classes","app","ReportesInventariosGral");

      $datosN = array();
      if($request['buscador'])
      {
        $datosN = $cls->ObtenerListadoProductosSinMovimiento($empresa['empresa'],$empresa['centro_utilidad'],$empresa['bodega'],$request['buscador'],$request['offset']);
        $request['buscador']['usuario_id'] = UserGetUID();
        $request['buscador']['empresa_id'] = $empresa['empresa'];
		$request['buscador']['centro_utilidad'] = $empresa['centro_utilidad'];
        $request['buscador']['bodega'] = $empresa['bodega'];
      }
      $action['volver'] = ModuloGetURL('app','ReportesInventariosGral','controller','Menu');
      $action['buscar'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesProductosMovimiento');
      $action['paginador'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesProductosMovimiento',array("buscador"=>$request['buscador']));
      
      $this->salida .= $mdl->FormaBuscarProductosMovimiento($action,$request['buscador'],$datosN, $cls->conteo, $cls->pagina);
      return true;
    }    
    /**
    * Funcion de control 
    *
    * @return boolean
    */
    function ListadoReportesProductosVencimiento()
    {
      $request = $_REQUEST;
      $empresa = SessionGetVar("PermisosReportesGral");
      
      $dias_vence = ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.trim($empresa['empresa']));
      $colores['PV'] = ModuloGetVar('app','ReportesInventariosGral','color_proximo_vencer');
      $colores['VN'] = ModuloGetVar('app','ReportesInventariosGral','color_vencido');
      
      $mdl = AutoCarga::factory("ReportesHTML","views","app","ReportesInventariosGral");
      $cls = AutoCarga::factory("ListaReportes","classes","app","ReportesInventariosGral");

      $datosN = array();
      //if($request['buscador'])
      //{
        $datosN = $cls->ObtenerListadoProductosVencimiento($empresa['empresa'],$empresa['centro_utilidad'],$empresa['bodega'],$request['buscador'],$dias_vence,$request['offset']);
        $request['buscador']['usuario_id'] = UserGetUID();
        $request['buscador']['empresa_id'] = $empresa['empresa'];
        $request['buscador']['centro_utilidad'] = $empresa['centro_utilidad'];
        $request['buscador']['bodega'] = $empresa['bodega'];
      //}
      $action['volver'] = ModuloGetURL('app','ReportesInventariosGral','controller','Menu');
      $action['buscar'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesProductosVencimiento');
      $action['paginador'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesProductosVencimiento',array("buscador"=>$request['buscador']));
      
      $this->salida .= $mdl->FormaBuscarProductosVencimiento($action,$request['buscador'],$datosN, $cls->conteo, $cls->pagina,$dias_vence,$colores);
      return true;
    }
      
    /**
    * Funcion de control 
    *
    * @return boolean
    */
    function ListadoReportesUsuariosCBarras()
    {
      $request = $_REQUEST;
      $empresa = SessionGetVar("PermisosReportesGral");
    
      $mdl = AutoCarga::factory("ReportesUsuarioCBarrasHTML","views","app","ReportesInventariosGral");
      $sql = AutoCarga::factory("ListaReportes","classes","app","ReportesInventariosGral");

      $datosN = array();
      //if($request['buscador'])
      //{
        $datosN = $sql->ObtenerListadoUsuarioCodBarras($empresa['empresa'],$request['buscador'],$request['offset']);
        $request['buscador']['usuario_id'] = UserGetUID();
        $request['buscador']['empresa_id'] = $empresa['empresa'];
      //}
      $action['volver'] = ModuloGetURL('app','ReportesInventariosGral','controller','Menu');
      $action['buscar'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesUsuariosCBarras');
      $action['paginador'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesUsuariosCBarras',array("buscador"=>$request['buscador']));
      
      $this->salida .= $mdl->FormaReporte($action,$request['buscador'],$datosN, $cls->conteo, $cls->pagina,$colores);
      return true;
    }
    
    /**
    * Funcion de control 
    *
    * @return boolean
    */
    function ListadoReportesEstadosPacientes()
    {
      $request = $_REQUEST;
      $empresa = SessionGetVar("PermisosReportesGral");
      
      $mdl = AutoCarga::factory("ReporteEstadosPacientesHTML","views","app","ReportesInventariosGral");
      $sql = AutoCarga::factory("ListaReportes","classes","app","ReportesInventariosGral");

      $TiposBloqueo=$sql->ObtenerTiposDeBloqueo();
      
      $datosN = array();

        $datosN = $sql->ObtenerPacientesEstados($request['TipoBloqueo'],'1',$request['offset']);
    
      $request['buscador']['usuario_id'] = UserGetUID();

      $action['volver'] = ModuloGetURL('app','ReportesInventariosGral','controller','Menu');
      $action['buscar'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesEstadosPacientes');
      $action['paginador'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesEstadosPacientes',array("buscador"=>$request['buscador']));
      
      $this->salida .= $mdl->FormaBuscarProveedor($action,$request['buscador'],$TiposBloqueo,$datosN, $sql->conteo, $sql->pagina);
      return true;
    }
    
    
      /**
    * Funcion de control 
    *
    * @return boolean
    */
    function ListadoReportesPendientesDespachar()
    {
      $request = $_REQUEST;
      $empresa = SessionGetVar("PermisosReportesGral");
      
      //print_r($empresa);
    
      $mdl = AutoCarga::factory("ReportePendientesDespacharHTML","views","app","ReportesInventariosGral");
      $sql = AutoCarga::factory("ListaReportes","classes","app","ReportesInventariosGral");

      
      $Farmacias=$sql->ObtenerFarmacias();
      $Prefijos=$sql->ObtenerPrefijosDespachosPendientes();
            
     // print_r($Prefijos);
      
      $datosN = array();
      
      $datosN = $sql->ObtenerDespachosPendientes($empresa['empresa'],$request['farmacia'],$request['buscador']);
      $_REQUEST['empresa_']=$empresa['empresa'];
      //print_r($datosN);
      
      $request['buscador']['usuario_id'] = UserGetUID();
     
      $action['volver'] = ModuloGetURL('app','ReportesInventariosGral','controller','Menu');
      $action['buscar'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesPendientesDespachar');
      $action['paginador'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesPendientesDespachar',array("buscador"=>$request['buscador']));
      
      $this->salida .= $mdl->Forma($action,$request['buscador'],$Farmacias,$Prefijos,$datosN, $cls->conteo, $cls->pagina,$colores);
      return true;
    }
    
     function ListadoReportesPendientesCompras()
    {
      $request = $_REQUEST;
      $empresa = SessionGetVar("PermisosReportesGral");
      
      //print_r($empresa);
    
      $mdl = AutoCarga::factory("ReportePendientesComprasHTML","views","app","ReportesInventariosGral");
      $sql = AutoCarga::factory("ListaReportes","classes","app","ReportesInventariosGral");
      
      $Proveedores=$sql->ObtenerProveedoresOC($empresa['empresa']);

     $num=count($Proveedores);
     //print_r($Proveedores);
      
      $datosN = array();
      
      $datosN = $sql->ObtenerComprasPendientes($empresa['empresa'],$request['codigo_proveedor_id'],$request['orden_pedido_id'],$request['buscador']);
      $_REQUEST['empresa_']=$empresa['empresa'];
      //print_r($datosN);
      
      $request['buscador']['usuario_id'] = UserGetUID();
     
      $action['volver'] = ModuloGetURL('app','ReportesInventariosGral','controller','Menu');
      $action['buscar'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesPendientesCompras');
      $action['paginador'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesPendientesCompras',array("buscador"=>$request['buscador']));
      
      $this->salida .= $mdl->Forma($action,$request['buscador'],$Proveedores,$datosN, $cls->conteo, $cls->pagina,$colores);
      return true;
    }
    
    
    function ListadoReportesLogAuditoria()
    {
      $request = $_REQUEST;
      $empresa = SessionGetVar("PermisosReportesGral");
            
      $mdl = AutoCarga::factory("ReportesLogAuditoriaHTML","views","app","ReportesInventariosGral");
      $cls = AutoCarga::factory("ListaReportes","classes","app","ReportesInventariosGral");

      $datosN = array();
      //print_r($request['buscador']);
      if($request['buscador'])
      {
        $datosN = $cls->ObtenerListadoLogAuditoria($empresa['empresa'],$request['buscador'],$request['offset']);
        $request['buscador']['usuario_id'] = UserGetUID();
        $request['buscador']['empresa_id'] = $empresa['empresa'];
      }
      $action['volver'] = ModuloGetURL('app','ReportesInventariosGral','controller','Menu');
      $action['buscar'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesLogAuditoria');
      $action['paginador'] = ModuloGetURL('app','ReportesInventariosGral','controller','ListadoReportesLogAuditoria',array("buscador"=>$request['buscador']));
      
      $this->salida .= $mdl->Forma($action,$request['buscador'],$datosN, $cls->conteo, $cls->pagina);
      return true;
    }    
         
    function ActasTecnicas()
    {
      $request = $_REQUEST;
      $empresa = SessionGetVar("PermisosReportesGral");
      IncludeFileModulo("Remotos_ActaTecnica","RemoteXajax","app","ReportesInventariosGral");
      $this->SetXajax(array("ActasTecnicas","BuscarActas"),null,"ISO-8859-1"); 
      
      $mdl = AutoCarga::factory("ReportesActasTecnicasHTML","views","app","ReportesInventariosGral");
      $cls = AutoCarga::factory("ListaReportes","classes","app","ReportesInventariosGral");

      $action['volver'] = ModuloGetURL('app','ReportesInventariosGral','controller','Menu');
            
      $this->salida .= $mdl->Forma($action);
      return true;
    }    

	
    function ConteoDiarioProductos()
    {
		$request = $_REQUEST;
		$empresa = SessionGetVar("PermisosReportesGral");
		
		$mdl = AutoCarga::factory("ReportesHTML","views","app","ReportesInventariosGral");
		$sql = AutoCarga::factory("ListaReportes","classes","app","ReportesInventariosGral");
				
		$request['info_empresa']['empresa'] = $empresa['empresa'];
        $request['info_empresa']['centro_utilidad'] = $empresa['centro_utilidad'];
        $request['info_empresa']['bodega'] = $empresa['bodega'];
        $request['info_empresa']['nombre_empresa'] = $empresa['razon_social'];
        $request['info_empresa']['nombre_bodega'] = $empresa['descripcion_bodega'];
        $request['info_empresa']['nombre_centro_utilidad'] = $empresa['descripcion_centro_utilidad'];
        
		$action['buscar'] = ModuloGetURL('app','ReportesInventariosGral','controller','ConteoDiarioProductos');
		$action['volver'] = ModuloGetURL('app','ReportesInventariosGral','controller','Menu');
		
		$this->salida .= $mdl->FormaSelectivo($action,$request);
		return true;
    }    
      
    function DespachosIngresados()
    {
		$request = $_REQUEST;
		$empresa = SessionGetVar("PermisosReportesGral");
		
		$mdl = AutoCarga::factory("ReportesHTML","views","app","ReportesInventariosGral");
		$sql = AutoCarga::factory("ListaReportes","classes","app","ReportesInventariosGral");
		
		$datos = $sql ->ObtenerDespachosIngresos($empresa,$request['buscador'],$request['offset']);
		$prefijos=$sql->ObtenerPrefijosDespachosFarmacias($empresa['empresa']);
		/*print_r($_REQUEST);*/
		$request['info_empresa']['empresa'] = $empresa['empresa'];
        $request['info_empresa']['centro_utilidad'] = $empresa['centro_utilidad'];
        $request['info_empresa']['bodega'] = $empresa['bodega'];
        $request['info_empresa']['nombre_empresa'] = $empresa['razon_social'];
        $request['info_empresa']['nombre_bodega'] = $empresa['descripcion_bodega'];
        $request['info_empresa']['nombre_centro_utilidad'] = $empresa['descripcion_centro_utilidad'];
        
		$action['buscar'] = ModuloGetURL('app','ReportesInventariosGral','controller','DespachosIngresados');
		$action['volver'] = ModuloGetURL('app','ReportesInventariosGral','controller','Menu');
		$action['paginador'] = ModuloGetURL('app','ReportesInventariosGral','controller','DespachosIngresados',array("buscador"=>$request['buscador']));
		
		$this->salida .= $mdl->FormaDespachosIngresados($action,$request,$prefijos,$datos,$sql->conteo, $sql->pagina);
		return true;
    }    
      
  }
?>