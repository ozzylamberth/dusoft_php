<?php
	/**
  * @package IPSOFT-SIIS
  * @version $Id: app_DevolucionVenta_controller.php,v 
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja Torres
  */
  /**
  * Clase Control: DevolucionVenta
  * Clase encargada del control de llamado de metodos en el modulo
  *
  * @package IPSOFT-SIIS
  * @version $Revision: 1.1 $
  * @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
  * @author Sandra Viviana Pantoja Torres
  */
	class app_DevolucionVenta_controller  extends classModulo
	{
    /**
    * Constructor de la clase
    */
     function app_DevolucionVenta_controller(){}
  /**
    *  Funcion principal del modulo
    *  @return boolean
    */
		  function main()
		  {
  			$request = $_REQUEST;
  			$contratacion = AutoCarga::factory('DevolucionVentaSQL', '', 'app', 'DevolucionVenta');
  			$permisos = $contratacion->ObtenerPermisos();    
  			
  			$ttl_gral = "ADMINISTRACIÒN DE FARMACIA";
  			$mtz[0] = 'FARMACIAS';
  			$mtz[1] = 'CENTRO DE UTILIDAD';
  			$mtz[2] = 'DEPARTAMENTO';
  			$mtz[3] = 'BODEGA';
  			$url[0] = 'app';
  			$url[1] = 'DevolucionVenta'; 
  			$url[2] = 'controller';
  			$url[3] = 'Dev_VentaProductos'; 
  			$url[4] = 'DevolucionVenta'; 
  			
  			$action['volver'] = ModuloGetURL('system', 'Menu');
  			$this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $permisos, $url, $action['volver']);
  			return true;
      }
   /**
    *  Funcion donde se buscan las facturas por venta de productos directos 
    *  @return boolean
    */
      function Dev_VentaProductos()
      {
          $request = $_REQUEST;
          if($request['DevolucionVenta']) 
          SessionSetVar("DatosEmpresaAF",$request['DevolucionVenta']); 
          $empresa = SessionGetVar("DatosEmpresaAF");
          $empresa_=$empresa['empresa_id'];
          $mdl = AutoCarga::factory("DevolucionVentaSQL", "classes", "app", "DevolucionVenta");
          $frmcontra = AutoCarga::factory("AdministracionFarmaciaHTML", "views", "app", "DevolucionVenta");
          $prefijo_venta = $mdl->ConsultarPrefijos_Venta_Directa($empresa);
          $documento = $mdl->ConsultarTipoId();
          $conteo =$pagina=0;
          if(!empty($request['buscador']))
			    { 
              $datos=$mdl->Consultar_Facturas_VD($empresa_,$request['buscador'],$request['offset']);
              $action['buscador']=ModuloGetURL('app','DevolucionVenta','controller','Dev_VentaProductos');
              $conteo= $mdl->conteo;
              $pagina= $mdl-> pagina;
           
			    }
          $inf=$mdl->EliminarTemportal($request);
          $action['paginador'] = ModuloGetURL('app', 'DevolucionVenta', 'controller', 'Dev_VentaProductos',array("buscador"=>$request['buscador']));
          $action['volver'] = ModuloGetURL("app", "DevolucionVenta", "controller", "main");
          $action['tercero'] = ModuloGetURL("app", "DevolucionVenta", "controller", "Detalle_Factura_Venta");
          $this->salida =$frmcontra->FormaBuscarProductosVenta($action,$conteo,$pagina,$request['buscador'],$datos,$prefijo_venta,$documento);
          return true;
      }
   /**
    *  Funcion donde se muestra el detalle de la Factura de venta
    *  @return boolean
    */
      function Detalle_Factura_Venta()
      {
          $request = $_REQUEST;
          $empresa = SessionGetVar("DatosEmpresaAF");
          $empresa_=$empresa['empresa_id'];
        
          if(!empty($request['prefijo'])  AND (!empty($request['factura_fiscal'])))
          {
              $informacion_tercero=$request['tipo_id_tercero']."-".$request['tercero_id']."-".$request['nombre_tercero'];
              $prefijo=$request['prefijo'];
              $factura_fiscal=$request['factura_fiscal'];
              SessionSetVar("Informacion_T",$informacion_tercero); 
              $Informacion_Terc = SessionGetVar("Informacion_T");
              SessionSetVar("prefijo",$prefijo); 
              $prefijo_venta = SessionGetVar("prefijo");
              SessionSetVar("factura_f",$factura_fiscal); 
              $factura_fis = SessionGetVar("factura_f");
          }
          
          $mdl = AutoCarga::factory("DevolucionVentaSQL", "classes", "app", "DevolucionVenta");
          $frmcontra = AutoCarga::factory("AdministracionFarmaciaHTML", "views", "app", "DevolucionVenta");
          $Inf_documento = $mdl->Consultar_Documento_Bodega_Ventas($empresa,$prefijo_venta,$factura_fis);
          $bodegas_doc_id=$Inf_documento[0]['bodegas_doc_id'];
          $bodegas_numeracion=$Inf_documento[0]['bodegas_numeracion'];
          $rc_prefijo=ModuloGetVar('app','DevolucionVenta','rc_prefijo_devolucion_farmacia_'.$empresa_);
          $esta=$mdl->Consultar_rc($empresa,$prefijo_venta,$factura_fis,$rc_prefijo);
          
          if(!empty($esta))
          {
              
            $total_fa=$request['saldo'];
            $bodegas_doc_id_rc=$esta[0]['bodegas_doc_id'];
            $bodegas_numeracion_rc=$esta[0]['bodegas_numeracion'];
            $informacion_detalle= $mdl->Productos_Sin_Devolucion_Factura($bodegas_doc_id,$bodegas_numeracion,$bodegas_doc_id_rc,$bodegas_numeracion_rc,$empresa_);
            $informacion_detalle_rc=$mdl->consultar_detalle_factura_venta_rc($bodegas_doc_id,$bodegas_numeracion,$bodegas_doc_id_rc,$bodegas_numeracion_rc,$empresa_);
			/*print_r($informacion_detalle_rc);
          print_r();*/
          }else
          {
            $total_fa=$request['total_factura'];
            $informacion_general = $mdl->Consultar_inf_Documento_Bodega_Ventas($bodegas_doc_id,$bodegas_numeracion);
            $informacion_detalle = $mdl->consultar_detalle_factura_venta($bodegas_doc_id,$bodegas_numeracion,$empresa_);

          }
          $action['volver'] = ModuloGetURL("app", "DevolucionVenta", "controller", "Dev_VentaProductos");
          $action['dventa'] = ModuloGetURL("app", "DevolucionVenta", "controller", "Producto_Devolver_Por_venta",array('total_fa'=>$total_fa));
		  
          $this->salida =$frmcontra->FormaDetalle_Factura_Venta($action,$empresa,$Informacion_Terc,$prefijo_venta,$factura_fis,$informacion_general,$informacion_detalle,$total_fa,$informacion_detalle_rc,$bodegas_doc_id_rc,$bodegas_numeracion_rc);
          return true;
      }    
    /**
    *  Funcion donde  se realiza la devolucion del producto
    *  @return boolean
    */
      function Producto_Devolver_Por_venta()
      {
          $request = $_REQUEST;
        
          $empresa = SessionGetVar("DatosEmpresaAF");
          $empresa_=$empresa['empresa_id'];
          $Informacion_Terc = SessionGetVar("Informacion_T");
          $prefijo_venta = SessionGetVar("prefijo");
          $factura_fis = SessionGetVar("factura_f");  
			
          $total_fa=$request['total_fa'];    
          $mdl = AutoCarga::factory("DevolucionVentaSQL", "classes", "app", "DevolucionVenta");
          $frmcontra = AutoCarga::factory("AdministracionFarmaciaHTML", "views", "app", "DevolucionVenta");
          $bodegas_doc= ModuloGetVar('app','DevolucionVenta','Ingreso_devolucion_por_venta_farmacia_'.trim($empresa_));

          $Inf_documento = $mdl->IngresarDocumentoTemporal($bodegas_doc,$request);
          $consulta_doc=$mdl->seleccionar_Maxdoc_tmp_id($bodegas_doc);
          $documento_tmp=$consulta_doc[0]['documento'];
          $ingresar_d=$mdl->Ingresar_detalle_bodegas_document($bodegas_doc,$request,$documento_tmp);
          $consulta_costo_dev=$mdl->Consultar_Costo_total_Dev($documento_tmp,$bodegas_doc);
          $costo_factura=$consulta_costo_dev[0]['total_costo'];
         
          $motivos=$mdl->Consultar_Motivos_Anulacion();
          $conceptos=$mdl->Consultar_Conceptos_nota_credito($empresa_);
          $action['volver'] = ModuloGetURL("app", "DevolucionVenta", "controller", "Dev_VentaProductos",array("temporal"=>$documento_tmp,"bodegas_doc"=>$bodegas_doc));
          if($costo_factura >=$request['total_costo'])
          {
              $action['anulacion'] = ModuloGetURL("app", "DevolucionVenta", "controller", "AnularFacturaCompleta", array("temporal"=>$documento_tmp,"bodegas_doc"=>$bodegas_doc));
              $this->salida =$frmcontra->FormaCrearDevolucionTotal($action,$empresa,$Informacion_Terc,$prefijo_venta,$factura_fis,$informacion_general,$informacion_detalle,$total_fa,$motivos);
          }
          else 
          {
             $action['parcial'] = ModuloGetURL("app", "DevolucionVenta", "controller", "DevolucionParcial", array("temporal"=>$documento_tmp,"bodegas_doc"=>$bodegas_doc));
            $this->salida =$frmcontra->FormaCrearDevolucion_Parcial($action,$empresa,$Informacion_Terc,$prefijo_venta,$factura_fis,$informacion_general,$informacion_detalle,$total_fa,$conceptos,$costo_factura);
          }
          return true;
      }    
    /**
    *  Funcion donde se realiza el proceso de anular una factura 
    *  @return boolean
    */ 
      function AnularFacturaCompleta()
      {
          $request = $_REQUEST;
          $observacion=$request['observacion'];
          $empresa = SessionGetVar("DatosEmpresaAF");
          $empresa_=$empresa['empresa_id'];
          $documento_id= ModuloGetVar('app','DevolucionVenta','documento_facturas_anuladas_venta_'.$empresa_);
          $rc_prefijo=ModuloGetVar('app','DevolucionVenta','rc_prefijo_devolucion_farmacia_'.$empresa_);
          $Informacion_Terc = SessionGetVar("Informacion_T");
          $prefijo_venta = SessionGetVar("prefijo");
          $factura_fis = SessionGetVar("factura_f");   
          $mdl = AutoCarga::factory("DevolucionVentaSQL", "classes", "app", "DevolucionVenta");
          $inf_prefijo_FA=$mdl->Consultar_Prefijo_Documentos($documento_id,$empresa_);
          $datos=$mdl->ConsultarInformacionTmp($request);
          $datos_d=$mdl->Consultar_Detalle_tmp($request);
          $info_numeraicon=$mdl->Consultar_numeracion_bodegas_doc($request);
          $numeracion=$info_numeraicon[0]['numeracion'];
          $tercero=explode("-",$Informacion_Terc);
          $temporal=$request['temporal'];
          $bodegas_doc=$request['bodegas_doc'];
          $anulacion=$mdl->Anulacion_Facturas_completa($empresa,$prefijo_venta,$factura_fis,$request,$inf_prefijo_FA,$tercero,$documento_id,$datos,$numeracion,$datos_d,$rc_prefijo);
          $this->Informativo_ProcesoAnuladas($mdl->numeracion,$mdl->bodegas_doc_id);
          return true;
      }
   /**
    *  Funcion donde se muestra el mensaje si se a anulado una factura 
    *  @return boolean
    */ 

      function Informativo_ProcesoAnuladas($numeracion,$bodegas_doc_id)
      {
        $request = $_REQUEST;
        $prefijo_venta = SessionGetVar("prefijo");
        $factura_fis = SessionGetVar("factura_f");   
        $action['volver'] = ModuloGetURL("app", "DevolucionVenta", "controller", "Dev_VentaProductos");
        $frmcontra = AutoCarga::factory("AdministracionFarmaciaHTML", "views", "app", "DevolucionVenta");
        $this->salida =$frmcontra->FormaMensaje($action,$prefijo_venta,$factura_fis,$numeracion,$bodegas_doc_id);
        return true;
      }
     /**
    *  Funcion donde se realiza el proceso para la devolucion parcial de una factura
    *  @return boolean
    */  
      function DevolucionParcial()
      {
          $request = $_REQUEST;
          $empresa = SessionGetVar("DatosEmpresaAF");
          $empresa_=$empresa['empresa_id'];
          $documento_id= ModuloGetVar('app','DevolucionVenta','documento_ncredito_devolucion_venta_farmacia_'.$empresa_);
          $rc_prefijo=ModuloGetVar('app','DevolucionVenta','rc_prefijo_devolucion_farmacia_'.$empresa_);
     
          $Informacion_Terc = SessionGetVar("Informacion_T");
          $prefijo_venta = SessionGetVar("prefijo");
          $factura_fis = SessionGetVar("factura_f");  
          $tercero=explode("-",$Informacion_Terc);        
          $mdl = AutoCarga::factory("DevolucionVentaSQL", "classes", "app", "DevolucionVenta");
          $datos_d=$mdl->Consultar_Detalle_tmp($request);
          $inf_prefijo_FA=$mdl->Consultar_Prefijo_Documentos($documento_id,$empresa_);
          $parcial=$mdl->Devolucion_Factura_parcial($empresa,$prefijo_venta,$factura_fis,$request,$datos_d,$inf_prefijo_FA,$documento_id,$tercero,$rc_prefijo);
          $this->Informacion_Factura_parcial($mdl->numeracion,$mdl->bodegas_doc_id);
          return true;
      }
   /**
    *  Funcion donde muestra el mensaje si se a realizado la devolucion parcial de la factura
    *  @return boolean
    */  
    function Informacion_Factura_parcial($numeracion,$bodegas_doc_id)
    {
      $request = $_REQUEST;
      $prefijo_venta = SessionGetVar("prefijo");
      $factura_fis = SessionGetVar("factura_f");   
      $action['volver'] = ModuloGetURL("app", "DevolucionVenta", "controller", "Dev_VentaProductos");
      $frmcontra = AutoCarga::factory("AdministracionFarmaciaHTML", "views", "app", "DevolucionVenta");
      $this->salida =$frmcontra->FormaMensajeParcial($action,$prefijo_venta,$factura_fis,$numeracion,$bodegas_doc_id);
      return true;
    }
      
	} 
?>