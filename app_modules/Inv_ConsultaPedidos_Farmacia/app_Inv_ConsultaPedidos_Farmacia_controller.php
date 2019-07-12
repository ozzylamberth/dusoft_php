<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_Inv_ConsultaPedidos_Farmacia_controller.php,v 1.1 2010/04/09 19:50:04 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina
  */
  /**
  * Clase Control: Inv_ConsultaPedidos_Farmacia
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina
  */
  class app_Inv_ConsultaPedidos_Farmacia_controller extends classModulo
  {
    /**
    * Constructor de la clase
    */
    function app_Inv_ConsultaPedidos_Farmacia_controller(){}
    /**
    * Funcion principal del modulo
    *
    * @return boolean
    */
    function main()
    {
      $cls = AutoCarga::factory("ListaReportes","classes","app","Inv_ConsultaPedidos_Farmacia");
      $permisos = $cls->ObtenerPermisos(UserGetUID());
      
      $titulo[0] = 'EMPRESAS';
			$url[0] = 'app';										//contenedor 
			$url[1] = 'Inv_ConsultaPedidos_Farmacia';	  //módulo 
			$url[2] = 'controller';							//clase 
			$url[3] = 'Menu';				            //método 
			$url[4] = 'reportes';					//indice del request
			$this->salida .= gui_theme_menu_acceso('PLANEACION DE REQUERIMIENTOS - DISTRIBUCION/SUMINISTRO',$titulo,$permisos,$url,ModuloGetURL('system','Menu'));

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
		
	  $cls = AutoCarga::factory("ListaReportes","classes","app","Inv_ConsultaPedidos_Farmacia");
	  $consulta_eliminados = $cls->GetPermisoConsulta(UserGetUID());
 
      $action['volver'] = ModuloGetURL('app','Inv_ConsultaPedidos_Farmacia','controller','main')."&reportes[empresa]=".$_REQUEST['reportes']['empresa']."";
      $action['logauditoria'] = ModuloGetURL('app','Inv_ConsultaPedidos_Farmacia','controller','ListadoReportesLogAuditoria')."&reportes[empresa]=".$_REQUEST['reportes']['empresa']."";
      $action['eliminar_reservapedidos'] = ModuloGetURL('app','Inv_ConsultaPedidos_Farmacia','controller','Eliminar_ReservaProductos')."&reportes[empresa]=".$_REQUEST['reportes']['empresa']."";
      $action['consulta_eliminados'] = ModuloGetURL('app','Inv_ConsultaPedidos_Farmacia','controller','Consultar_ReservaEliminados');
	  $mdl = AutoCarga::factory("MensajesModuloHTML","views","app","Inv_ConsultaPedidos_Farmacia");
    
      $this->salida .= $mdl->FormaMenuInicial($action,$consulta_eliminados);
      return true;
    }

    
    
    function ListadoReportesLogAuditoria()
    {
      $request = $_REQUEST;
      $empresa = SessionGetVar("PermisosReportesGral");
            
      $mdl = AutoCarga::factory("ReportesLogAuditoriaHTML","views","app","Inv_ConsultaPedidos_Farmacia");
      $cls = AutoCarga::factory("ListaReportes","classes","app","Inv_ConsultaPedidos_Farmacia");

      $datosN = array();
      //print_r($request['buscador']);
      
      $esm_empresas = $cls->ObtenerEsm();
      
      
      if($request['buscador'])
      {
        $datosN = $cls->Obtener_Reporte($empresa['empresa'],$request['buscador'],$request['offset']);
        $request['buscador']['usuario_id'] = UserGetUID();
        $request['buscador']['empresa_id'] = $empresa['empresa'];
      }
      $action['volver'] = ModuloGetURL('app','Inv_ConsultaPedidos_Farmacia','controller','Menu')."&reportes[empresa]=".$_REQUEST['reportes']['empresa']."";
      $action['buscar'] = ModuloGetURL('app','Inv_ConsultaPedidos_Farmacia','controller','ListadoReportesLogAuditoria')."&reportes[empresa]=".$_REQUEST['reportes']['empresa']."";
      $action['paginador'] = ModuloGetURL('app','Inv_ConsultaPedidos_Farmacia','controller','ListadoReportesLogAuditoria',array("buscador"=>$request['buscador']))."&reportes[empresa]=".$_REQUEST['reportes']['empresa']."";
      
      $this->salida .= $mdl->Forma($action,$request['buscador'],$datosN,$esm_empresas, $cls->conteo, $cls->pagina);
      return true;
    }    
    
    function Eliminar_ReservaProductos()
    {
      $request = $_REQUEST;
	  //var_dump($request); 
      $empresa = SessionGetVar("PermisosReportesGral");
      IncludeFileModulo("Remotos","RemoteXajax","app","Inv_ConsultaPedidos_Farmacia");
			$this->SetXajax(array("BorrarItem_Reservado","Borrar"));
       $this->IncludeJS("CrossBrowser");
				$this->IncludeJS("CrossBrowserEvent");
				$this->IncludeJS("CrossBrowserDrag");	
      
      $mdl = AutoCarga::factory("ReportesLogAuditoriaHTML","views","app","Inv_ConsultaPedidos_Farmacia");
      $cls = AutoCarga::factory("ListaReportes","classes","app","Inv_ConsultaPedidos_Farmacia");

      $datosN = array();
      //print_r($request['buscador']);
      
      $esm_empresas = $cls->ObtenerEsm();
      //$REQUEST[];
      
      if($request['buscador'])
      {
        $datosN = $cls->Obtener_ReporteDetalle($empresa['empresa'],$request['buscador'],$request['offset']);
        $request['buscador']['usuario_id'] = UserGetUID();
        $request['buscador']['empresa_id'] = $empresa['empresa'];
      }
      $action['volver'] = ModuloGetURL('app','Inv_ConsultaPedidos_Farmacia','controller','Menu')."&reportes[empresa]=".$_REQUEST['reportes']['empresa']."";
      $action['buscar'] = ModuloGetURL('app','Inv_ConsultaPedidos_Farmacia','controller','Eliminar_ReservaProductos')."&reportes[empresa]=".$_REQUEST['reportes']['empresa']."";
      $action['paginador'] = ModuloGetURL('app','Inv_ConsultaPedidos_Farmacia','controller','Eliminar_ReservaProductos',array("buscador"=>$request['buscador']))."&reportes[empresa]=".$_REQUEST['reportes']['empresa']."";;
      
      $this->salida .= $mdl->Forma_EliminarReserva($action,$request['buscador'],$datosN,$esm_empresas, $cls->conteo, $cls->pagina);
      return true;
    }    

    /*************************************************************
	Consultar los pedidos reservados eliminados por 
	usuarios de la aplicacion   20092012
	**************************************************************/
    function Consultar_ReservaEliminados()
    {
      $empresa = SessionGetVar("PermisosReportesGral");
	  $_REQUEST['reportes']['empresa'] = $empresa['empresa'];
	  $request = $_REQUEST;
	  //var_dump($request);     
     
      $this->IncludeJS("CrossBrowser");
	  $this->IncludeJS("CrossBrowserEvent");
	  $this->IncludeJS("CrossBrowserDrag");	
      
      $view = AutoCarga::factory("ReportesLogAuditoriaHTML","views","app","Inv_ConsultaPedidos_Farmacia");
      $cls   = AutoCarga::factory("ListaReportes","classes","app","Inv_ConsultaPedidos_Farmacia");

      $datos = array();

	  $datos = $cls->Obtener_DetalleEliminados($request['offset']);
      
      $action['volver'] = ModuloGetURL('app','Inv_ConsultaPedidos_Farmacia','controller','Menu')."&reportes[empresa]=".$_REQUEST['reportes']['empresa']."";
      $action['paginador'] = ModuloGetURL('app','Inv_ConsultaPedidos_Farmacia','controller','Consultar_ReservaEliminados')."&reportes[empresa]=".$_REQUEST['reportes']['empresa']."";
      
      $this->salida .= $view->Forma_ConsultaEliminados($action,$datos,$cls->conteo,$cls->pagina);
      return true;
    }   


    /***************************************************************
    * Funcion de impresion general de pedidos farmacia    *
    * @return boolean
    ***************************************************************/
    function MostrarPedidosGral()
    {
      $request = $_REQUEST;
	  $pedidoid =  $_REQUEST['pedido'];

	  $mdl = AutoCarga::factory("MensajesModuloHTML","views","app","Inv_ConsultaPedidos_Farmacia");
	       
      //$action['volver'] = ModuloGetURL('app','Inv_ConsultaPedidos_Farmacia','controller','Menu')."&reportes[empresa]=".$_REQUEST['reportes']['empresa']."";
     
     
      $this->salida .= $mdl->FormaPedGral($action,$pedidoid);
      return true;
    }

	
	
  }
?>