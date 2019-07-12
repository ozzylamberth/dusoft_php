<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_ESM_PlaneacionRequerimientos_controller.php,v 1.1 2010/04/09 19:50:04 hugo Exp $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina
  */
  /**
  * Clase Control: ESM_PlaneacionRequerimientos
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Mauricio Medina
  */
  class app_ESM_PlaneacionRequerimientos_controller extends classModulo
  {
    /**
    * Constructor de la clase
    */
    function app_ESM_PlaneacionRequerimientos_controller(){}
    /**
    * Funcion principal del modulo
    *
    * @return boolean
    */
    function main()
    {
      $cls = AutoCarga::factory("ListaReportes","classes","app","ESM_PlaneacionRequerimientos");
      $permisos = $cls->ObtenerPermisos(UserGetUID());
      
      $titulo[0] = 'EMPRESAS';
			$url[0] = 'app';										//contenedor 
			$url[1] = 'ESM_PlaneacionRequerimientos';	  //módulo 
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
      
      $action['volver'] = ModuloGetURL('app','ESM_PlaneacionRequerimientos','controller','main');
      $action['logauditoria'] = ModuloGetURL('app','ESM_PlaneacionRequerimientos','controller','ListadoReportesLogAuditoria');
      $mdl = AutoCarga::factory("MensajesModuloHTML","views","app","ESM_PlaneacionRequerimientos");
    
      $this->salida .= $mdl->FormaMenuInicial($action);
      return true;
    }

    
    
    function ListadoReportesLogAuditoria()
    {
      $request = $_REQUEST;
      $empresa = SessionGetVar("PermisosReportesGral");
            
      $mdl = AutoCarga::factory("ReportesLogAuditoriaHTML","views","app","ESM_PlaneacionRequerimientos");
      $cls = AutoCarga::factory("ListaReportes","classes","app","ESM_PlaneacionRequerimientos");

      $datosN = array();
      //print_r($_REQUEST);
      /*
      Insercion de los elementos temporales de la pre- orden de requisicion
      */
      if($_REQUEST['cantidad_registros']>0)
      {
        for($i=0;$i<$_REQUEST['cantidad_registros'];$i++)
        {
        if($_REQUEST['op'][$i]!="")
          {
           if($_REQUEST['pedido'][$i]>0 && $_REQUEST['pedido'][$i]!="")
           {
           $token=$cls->Insertar_PreOrdenRequisicion($_REQUEST['tipo_id_tercero'][$i],
           $_REQUEST['tercero_id'][$i],
           $_REQUEST['codigo_producto'][$i],
           $_REQUEST['empresa_id'][$i],
           $_REQUEST['centro_utilidad_destino'][$i],
           $_REQUEST['bodega_destino'][$i],
           $_REQUEST['pedido'][$i]);
           }
          }
        }
      }
      
      
      $esm_empresas = $cls->ObtenerEsm();
      
      
      if($request['buscador'])
      {
        $datosN = $cls->Obtener_Reporte($empresa['empresa'],$request['buscador'],$request['offset']);
        $request['buscador']['usuario_id'] = UserGetUID();
        $request['buscador']['empresa_id'] = $empresa['empresa'];
      }
      $action['volver'] = ModuloGetURL('app','ESM_PlaneacionRequerimientos','controller','Menu');
      $action['buscar'] = ModuloGetURL('app','ESM_PlaneacionRequerimientos','controller','ListadoReportesLogAuditoria');
      $action['guardar_tmp'] = ModuloGetURL('app','ESM_PlaneacionRequerimientos','controller','ListadoReportesLogAuditoria');
      $action['preordenes'] = ModuloGetURL('app','ESM_PlaneacionRequerimientos','controller','Ver_Preordenes_Requisicion');
      $action['paginador'] = ModuloGetURL('app','ESM_PlaneacionRequerimientos','controller','ListadoReportesLogAuditoria',array("buscador"=>$request['buscador']));
      
      $this->salida .= $mdl->Forma($action,$request['buscador'],$datosN,$esm_empresas, $cls->conteo, $cls->pagina);
      return true;
    }    
    
    function Ver_Preordenes_Requisicion()
    {
      $request = $_REQUEST;
      //
      $empresa = SessionGetVar("PermisosReportesGral");
            
      $mdl = AutoCarga::factory("ReportesLogAuditoriaHTML","views","app","ESM_PlaneacionRequerimientos");
      $cls = AutoCarga::factory("ListaReportes","classes","app","ESM_PlaneacionRequerimientos");

      /*
      En Caso de que La Accion sea Guardar el temporal
      */
      if($_REQUEST['registros']!="")
      {
      $token=$cls->Guardar_RequisicionTemporal($_REQUEST);
      //print_r($token);
      }
      
      /*Eliminacion de Items*/
      if($_REQUEST['ssiidd']!="")
      {
      $cls->Eliminar_Item($_REQUEST['ssiidd']);
      }
      
      //print_r($_REQUEST);
      $datosN = array();
            
      $datosN = $cls->Agrupacion_Empresas();
      
      $action['volver'] = ModuloGetURL('app','ESM_PlaneacionRequerimientos','controller','ListadoReportesLogAuditoria');
      $action['eliminar'] = ModuloGetURL('app','ESM_PlaneacionRequerimientos','controller','Ver_Preordenes_Requisicion');
      $action['crear_requisicion_tmp'] = ModuloGetURL('app','ESM_PlaneacionRequerimientos','controller','Ver_Preordenes_Requisicion');
      $action['paginador'] = ModuloGetURL('app','ESM_PlaneacionRequerimientos','controller','ListadoReportesLogAuditoria',array("buscador"=>$request['buscador']));
      if($token)
      {
      $html .= "	      <fieldset class=\"fieldset\">\n";
      $html .= "          <legend class=\"normal_10AN;label_error\">MENSAJE</legend>\n";
      $html .= "          <table>";
      $html .= "            <tr>";
      $html .= "              <td class=\"label_error\">SE CREO CON EXITO, LA REQUISION TEMPORAL: ".$token['orden_requisicion_tmp_id']."</td>";
      $html .= "            </tr>";
      $html .= "          </table>";
      $html .= "        </fieldset><BR>";
      }
      $html .= $mdl->Forma_PreOrdenesRequisicion($action,$request['buscador'],$datosN, $cls->conteo, $cls->pagina);
      $this->salida .= $html;
      return true;
    }
      
  }
?>