<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: app_AdminFarmacia_controller.php,v 1.0
	* @copyright (C) 2009 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/
	/**
	* Clase Control: AdminFarmacia
	* Clase encargada del control de llamado de metodos en el modulo
	*
	* @package IPSOFT-SIIS
	/*/
	class app_Consulta_Productos_controller  extends classModulo
	{
	/**
		* Constructor de la clase
	*/
	function app_Consulta_Productos_controller()
	{}
	/**
        *  Funcion principal del modulo
        *  @return boolean
    */
		function Main()
		{
			$request = $_REQUEST;
			$datos = AutoCarga::factory('AdminFarmaciaSQL', '', 'app', 'AdminFarmacia');
			$permisos = $datos->ObtenerPermisos();    
			$ttl_gral = "ADMINISTRACIÒN DE FARMACIA";
			$mtz[0]='FARMACIAS';
			$url[0] = 'app';
			$url[1] = 'Consulta_Productos'; 
			$url[2] = 'controller';
			$url[3] = 'Menu'; 
			$url[4] = 'Consulta_Productos'; 
			$action['buscador']=ModuloGetURL('app','Consulta_Productos','controller','BusquedaProductosFarmacias');
			$action['volver'] = ModuloGetURL('system', 'Menu');
			$this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $permisos, $url, $action['buscador']);
			
			return true;
		}    
		
		/*{
			$request = $_REQUEST;
			
		    $emp = SessionGetVar("DatosFarmacia");
			$nombreFarma=$emp['descripcion1'];
			$documento_id = SessionGetVar("document");
			$centro = SessionGetVar("DatosCentro");
			$centrou=$centro['centro_utilidad'];
			$bodega = SessionGetVar("DatosBodega");
			$bodegau=$bodega['bodega'];
			$farmacia=$centro['empresa_id'];
			$Nombrecentro=$centro['descripcion'];
			$Nombrebodega=$bodega['descripcion3'];
			$documento_id = SessionGetVar("document");
			
			$mdl = AutoCarga::factory("AdminFarmaciaSQL", "classes", "app", "AdminFarmacia");
				$conteo =$pagina=0;
				if(!empty($request['buscador']))
				{
					$datos=$mdl->BuscarProducPorFarmacia($bodegau,$request['buscador'],$request['offset']);
					//$action['buscador']=ModuloGetURL('app','AdminFarmacia','controller','BusquedaProductosFarmacias');
					$conteo= $mdl->conteo;
					$pagina= $mdl-> pagina;
				}
							
			$action['paginador'] = ModuloGetURL('app', 'AdminFarmacia', 'controller', 'BusquedaProductosFarmacias',array("buscador"=>$request['buscador'],"far"=>$far,"Centrid"=>$Centrid,"bod"=>$bod));
			//$action['volver'] = ModuloGetURL("app", "AdminFarmacia", "controller", "MenuPrincipal",array("AdminFarmacia"=>$request['AdminFarmacia']));
			$action['volver'] = ModuloGetURL('system', 'Menu');
			$frmcontra = AutoCarga::factory("AdminFarmaciaHTML", "views", "app", "AdminFarmacia");
			$this->salida =$frmcontra->FormaBuscarProductosFarmacia($action,$datos,$conteo,$pagina,$request['buscador']);
			return true;
		}*/
		
		
	/*
		* Funcion de control para el Menu Inicial Centro Utilidad
		 *  @return boolean
	*/
	
 	    function Menu()
		{
			
			$request = $_REQUEST;
			
			if($request['AdminFarmacia']) SessionSetVar("DatosFarmacia",$request['AdminFarmacia']);
			$emp = SessionGetVar("DatosFarmacia");
			$empresa=$emp['empresa_id'];
			
			$contratacion = AutoCarga::factory('AdminFarmaciaSQL', '', 'app', 'AdminFarmacia');
			$permisos = $contratacion->ListarCentrodeUtilidad($empresa);
			$ce='CENTRO DE UTILIDAD';
			$ttl_gral = " CENTRO DE UTILIDAD";
			$mtz[0]=$ce;
			$url[0] = 'app';
			$url[1] = 'AdminFarmacia'; 
			$url[2] = 'controller';
			$url[3] = 'MenuBodega'; 
			$url[4] = 'AdminFarmacia'; 
     		$action['volver'] = ModuloGetURL("app", "AdminFarmacia", "controller", "Main");
			
			$this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $permisos, $url, $action['volver']);
			return true;
		}    
	/*
		* Funcion de control para el Menu bodegas
		*  @return boolean
	*/
		function MenuBodega()
		{
			$request = $_REQUEST;
			if($request['AdminFarmacia']) SessionSetVar("DatosCentro",$request['AdminFarmacia']);
			$centro = SessionGetVar("DatosCentro");
			$centrou=$centro['centro_utilidad'];
			$emp = SessionGetVar("DatosFarmacia");
			$empresa=$emp['empresa_id'];
			$contratacion = AutoCarga::factory('AdminFarmaciaSQL', '', 'app', 'AdminFarmacia');
			$permisos = $contratacion->ObtenerBodegaFarmacia($empresa,$centrou);
			$ce='BODEGAS';
			$ttl_gral = " BODEGAS";
			$mtz[0]=$ce;
			$url[0] = 'app';
			$url[1] = 'AdminFarmacia'; 
			$url[2] = 'controller';
			$url[3] = 'MenuPrincipal'; 
			$url[4] = 'AdminFarmacia'; 
     		$action['volver'] = ModuloGetURL("app", "AdminFarmacia", "controller", "Menu");
			$this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $permisos, $url, $action['volver']);
		
			return true;      
		}
		/*
		* Funcion Contiene Un Menu Con Diferentes Opciones:
		*  @return boolean
	*/
		
		function MenuPrincipal()
		{
			$request = $_REQUEST;
			
			$emp = SessionGetVar("DatosEmpresaAF");
			$empresa=$emp['empresa_id'];
			$centro = SessionGetVar("DatosCentro");
			$centrou=$centro['centro_utilidad'];
			if($request['AdminFarmacia']) SessionSetVar("DatosBodega",$request['AdminFarmacia']);
			$bodega = SessionGetVar("DatosBodega");
			$bodegau=$bodega['bodega'];
			
			$action['documentos'] = ModuloGetURL("app", "AdminFarmacia", "controller", "MenuDocumentos",array("AdminFarmacia"=>$request['AdminFarmacia']));
			$action['productos'] = ModuloGetURL("app", "AdminFarmacia", "controller", "BusquedaProductosFarmacias",array("AdminFarmacia"=>$request['AdminFarmacia']));
			
			$action['volver'] = ModuloGetURL("app", "AdminFarmacia", "controller", "MenuBodega");
			$act = AutoCarga::factory("AdminFarmaciaHTML", "views", "app", "AdminFarmacia");
			$this->salida = $act->FormaMenu($action);
			return true;
		}
		
	/*
		* Funcion Menu para los documentos de la farmacia
		*  @return boolean
	*/
		
		function MenuDocumentos()
		{
			$request = $_REQUEST;
			
			$emp = SessionGetVar("DatosEmpresaAF");
			$empresa=$emp['empresa_id'];
			$centro = SessionGetVar("DatosCentro");
			$centrou=$centro['centro_utilidad'];
			$bodega = SessionGetVar("DatosBodega");
			$bodegau=$bodega['bodega'];
			$centroid=$centro['centro_utilidad'];

			$this->SetXajax(array("EmpresaDestino","TransEmpresaDestino"),"app_modules/AdminFarmacia/RemoteXajax/DatosEmpresas.php","ISO-8859-1");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			
			$action['ingreso'] = ModuloGetURL("app", "AdminFarmacia", "controller", "ConsultaDocDespacho");
			
			$action['volver'] = ModuloGetURL("app", "AdminFarmacia", "controller", "MenuPrincipal",array("AdminFarmacia"=>$request['AdminFarmacia']));
			$act = AutoCarga::factory("AdminFarmaciaHTML", "views", "app", "AdminFarmacia");
			$this->salida = $act->FormaMenuDocumentos($action);
			return true;
		}
	/* CREAR UN DOCUMENTO DE INGRESO APARTIR DE UN DOCUMENTO DE DESPACHO 
		*  Funcion Donde se busca y se Selecciona:
		* la Empresa que Contiene el documento de Bodega Principal
		* Se busca por Prefijo y/o Numero del documento 
	*/
		function ConsultaDocDespacho()
		{
			$request = $_REQUEST;
			$emp = SessionGetVar("DatosEmpresaAF");
			$empresa=$emp['empresa_id'];
			$centro = SessionGetVar("DatosCentro");
			$centrou=$centro['centro_utilidad'];
			$bodega = SessionGetVar("DatosBodega");
			$bodegau=$bodega['bodega'];
			$farmacia=$centro['empresa_id'];
			$centroid=$centro['centro_utilidad'];

			
			
			IncludeFileModulo("DatosEmpresas","RemoteXajax","app","AdminFarmacia");
			$this->SetXajax(array("MostrarPrefijos"),null,"ISO-8859-1" );
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			
			$mdl = AutoCarga::factory("AdminFarmaciaSQL", "classes", "app", "AdminFarmacia");
			$DatosEmp=$mdl->ConsultarPendientesPorConfirmar($farmacia);
			
			$act = AutoCarga::factory("AdminFarmaciaHTML", "views", "app", "AdminFarmacia");
			$conteo =$pagina=0;
			
			$docIngreso= ModuloGetVar('app','AdminFarmacia','documento_ingreso_farmacia_'.trim($farmacia));
       
			if(!empty($request['buscador'] ))
			{
				$empres=$request['empresas'];
				
				$prefijo=$request['prefijo'];
				$numero=$request['numero'];
				$bodega=$request['bodega'];
				$datos=$mdl->ObtenerFiltrosDocDespacho($request['buscador'],$request['offset'],$empres,$prefijo,$farmacia);
				
				/*$doc_id=$datos[0]['bodegas_doc_id'];
				$dat=$mdl->ConsultarDatosDe($doc_id);
				$numeracion=$dat['0']['numeracion'];*/
			
				
				$action['buscador']=ModuloGetURL('app','AdminFarmacia','controller','ConsultaDocDespacho');
				$action['paginador'] = ModuloGetURL("app", "AdminFarmacia", "controller", "ConsultaDocDespacho");
				$conteo= $mdl->conteo;
				$pagina= $mdl-> pagina;
				$action['confir'] = ModuloGetURL("app", "AdminFarmacia", "controller", "ListarProductosBodMov",array("bodega"=>$bodega));
				$action['pendie'] = ModuloGetURL("app", "AdminFarmacia", "controller", "ListarProductosPendientes",array("bodega"=>$bodega));
			}
			$action['volver'] = ModuloGetURL("app", "AdminFarmacia", "controller", "MenuDocumentos");
			$this->salida= $act->FormaBuscarEmpresas2($action,$request,$datos,$conteo,$pagina,$DatosEmp,$excon,$bodegau,$doc_id,$numeracion,$docIngreso,$empres,$farmacia);
			return true;
		}
		/* 
		*Funcion que permite Listar Los productos que estan en Un Documento 
		**  @return boolean 
	*/
		function ListarProductosBodMov()
		{
			$request = $_REQUEST;
			IncludeFileModulo("DatosEmpresas","RemoteXajax","app","AdminFarmacia");
			$this->SetXajax(array("OrganizarInfor","GuardarTemporal","EliminarProductos","MostrarFormaProductosCompleta","TransVariables","MarcadoNull","GuardarTemporalProductCompletos","EliminarProductosTodosV"),"app_modules/AdminFarmacia/RemoteXajax/DatosEmpresas.php","ISO-8859-1");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			
			
			$mdl = AutoCarga::factory("AdminFarmaciaSQL", "classes", "app", "AdminFarmacia");
			$prefijo=$request['prefijo'];
			$numero=$request['numero'];
			$empresa_envia=$request['empresa_envia'];
			
			
			
			$centro = SessionGetVar("DatosCentro");
			$centrou=$centro['centro_utilidad'];
			$bodega = SessionGetVar("DatosBodega");
			$bodegau=$bodega['bodega'];
			$farmacia=$centro['empresa_id'];
			
      $conteo= $mdl->conteo;
			$pagina= $mdl-> pagina;
			$Datos=$mdl->ObtenerInfMoviDetalle($prefijo,$numero,$empresa_envia);
			$abreviatura_estado=$Datos[0]['abreviatura_estado'];
			$farmacia=$Datos[0]['farmacia_id'];
			
			$conta=$mdl->contarDatos($prefijo,$numero,$empresa_envia);
		
			$contador=$conta[0]['c'];
			$action['paginador'] = ModuloGetURL("app", "AdminFarmacia", "controller", "ListarProductosBodMov");
			$action['volver'] = ModuloGetURL("app", "AdminFarmacia", "controller", "ConsultaDocDespacho");
			$act = AutoCarga::factory("AdminFarmaciaHTML", "views", "app", "AdminFarmacia");
			$this->salida = $act->FormaListaProductBodMov($action,$Datos,$request,$conteo,$pagina,$prefijo,$numero,$empresa_envia,$farmacia,$bodegau,$contador,$centrou,$abreviatura_estado);
		    return true;
		}
	/*
		* Funcion que permite Generar el Documento
		* de Ingreso de productos al Inventario de la Farmacia.
		**  @return boolean 
	*/		
		function GenerarDocumento()
		{
			$request=$_REQUEST;  
		
    IncludeFileModulo("Remotos_ActaTecnica","RemoteXajax","app","AdminFarmacia");
		$this->SetXajax(array("FormaActa","RegistrarActaTecnica","VerDocumentoCreado","ActasTecnicas"),null,"ISO-8859-1");

		$this->IncludeJS("CrossBrowser");
		$this->IncludeJS("CrossBrowserEvent");
		$this->IncludeJS("CrossBrowserDrag");	
		
    $this->IncludeJS("TabPaneLayout");
		$this->IncludeJS("TabPaneApi");
    $this->IncludeJS("TabPane");
    
			$centro = SessionGetVar("DatosCentro");
			$centrou=$centro['centro_utilidad'];
			$bodega = SessionGetVar("DatosBodega");
			$bodegau=$bodega['bodega'];
			$farmacia=$centro['empresa_id'];
			
			$observacion=$request['observacion'];
			$abrev_estado=$request['abrev_estado']; 
			$prefijo=$request['prefijo']; 
			$numeracion=$request['numeracion']; 
			
			if($abrev_estado=="")
			{
				
				$abrev_estado='null';
			}
     
      $mdl = AutoCarga::factory("AdminFarmaciaSQL", "classes", "app", "AdminFarmacia");
      $docIngreso= ModuloGetVar('app','AdminFarmacia','documento_ingreso_farmacia_'.trim($farmacia));
   
      if($docIngreso!='')
      {
      	    $InvBoDo=$mdl->Consultarinv_bodegas_documentos($docIngreso,$farmacia,$centrou,$bodegau);
         
      			$Docume=$mdl->SelecPrefijoNumerodocumentos($docIngreso,$farmacia);
      			$preIng=$Docume[0]['prefijo'];
            
      			$temporal=$mdl->TraerInformacionTemporal($prefijo,$numeracion);
         
           if(!empty($temporal))
			      {
		
			        if(!empty($InvBoDo))
				      {
              		$numeracionIng=$Docume[0]['numeracion'];
        					$temporal=$mdl->TraerInformacionTemporal($prefijo,$numeracion);
        					$pendient=$mdl->ConsultarTemporales($prefijo,$numeracion,$farmacia);
        					$Ing=$mdl->GenerarDocumentoIngresoInventarioFarmacia($docIngreso,$farmacia,$centrou,$bodegau,$preIng,$numeracionIng,$observacion,$abrev_estado);
        					$IngD=$mdl->GenerarDoumentoInv_bodega_movimiento_id($farmacia,$centrou,$bodegau,$preIng,$numeracionIng,$temporal);
        					$E_tmp=$mdl->EliminarTemporalProductosVerif($farmacia,$prefijo,$numeracion);
                  if(empty($pendient))
                  {
                    $act=$mdl->ActualizarInformacion($prefijo,$numeracion,$farmacia);
                  }
        					$TraerDocumentoGenero=$mdl->MostrarNumeracionDocumento($docIngreso,$farmacia,$centrou,$bodegau,$preIng);
        					$numeroGenerado=$TraerDocumentoGenero [0]['movimiento'];
                                   
                  $TraerDocumentoGenero=$mdl->MostrarNumeracionDocumento($docIngreso,$farmacia,$centrou,$bodegau,$preIng);
                  $numeroGenerado=$TraerDocumentoGenero [0]['movimiento'];
                  $action['volver'] = ModuloGetURL("app", "AdminFarmacia", "controller", "MenuDocumentos") ;     
                  $frmcontra = AutoCarga::factory("AdminFarmaciaHTML", "views", "app", "AdminFarmacia");
                  $this->salida =$frmcontra->FormaMensajeGnerarDocumento($action,$numeroGenerado,$preIng,$farmacia);

                  
              }else
              {
                		$IngvBoDo=$mdl->Ingresoinv_bodegas_documentos($docIngreso,$farmacia,$centrou,$bodegau);
                    if(empty($IngvBoDo))
                    {               
                      $msg1 =$mdl->mensajeDeError;
                      $action['volver'] = ModuloGetURL("app", "AdminFarmacia", "controller", "MenuDocumentos") ; 
                      $Inf_=$mdl->EliminarTemporales($farmacia);
                      $frmcontra = AutoCarga::factory("AdminFarmaciaHTML", "views", "app", "AdminFarmacia");
                      $this->salida =$frmcontra->FormaMensajeDocumento($action,$msg1);
                      
                    }else
                    {
                             
            						$Docume=$mdl->SelecPrefijoNumerodocumentos($docIngreso,$farmacia);
            						$preIng=$Docume [0]['prefijo'];
            						$numeracionIng=$Docume[0]['numeracion'];
            						$temporal=$mdl->TraerInformacionTemporal($prefijo,$numeracion);

            						$pendient=$mdl->ConsultarTemporales($prefijo,$numeracion,$farmacia);
            						$Ing=$mdl->GenerarDocumentoIngresoInventarioFarmacia($docIngreso,$farmacia,$centrou,$bodegau,$preIng,$numeracionIng,$observacion,$abrev_estado);
            						$IngD=$mdl->GenerarDoumentoInv_bodega_movimiento_id($farmacia,$centrou,$bodegau,$preIng,$numeracionIng,$temporal);
            						$E_tmp=$mdl->EliminarTemporalProductosVerif($farmacia,$prefijo,$numeracion);
                        if(empty($pendient))
        						    {
        							    $act=$mdl->ActualizarInformacion($prefijo,$numeracion,$farmacia);
        						    }
        		            $TraerDocumentoGenero=$mdl->MostrarNumeracionDocumento($docIngreso,$farmacia,$centrou,$bodegau,$preIng);
                        $numeroGenerado=$TraerDocumentoGenero [0]['movimiento'];
                        
                        $TraerDocumentoGenero=$mdl->MostrarNumeracionDocumento($docIngreso,$farmacia,$centrou,$bodegau,$preIng);
              					$numeroGenerado=$TraerDocumentoGenero [0]['movimiento'];
              					$action['volver'] = ModuloGetURL("app", "AdminFarmacia", "controller", "MenuDocumentos") ;     
                    		$frmcontra = AutoCarga::factory("AdminFarmaciaHTML", "views", "app", "AdminFarmacia");
                    		$this->salida =$frmcontra->FormaMensajeGnerarDocumento($action,$numeroGenerado,$preIng,$farmacia);
                                      
                    }
    	        }
            }
      }else
        {
          $action['volver'] = ModuloGetURL("app", "AdminFarmacia", "controller", "MenuDocumentos") ; 
          $Inf_=$mdl->EliminarTemporales($farmacia);
          $frmcontra = AutoCarga::factory("AdminFarmaciaHTML", "views", "app", "AdminFarmacia");
          $this->salida =$frmcontra->FormaMensajeError($action,$farmacia);
        }
          return true;
        }
		
/*
		* Funcion que lista los productos pendientes que han  quedado Despues de generar el documento de ingreso.
		**  @return boolean 
	*/
		function ListarProductosPendientes()
		{	
			$request = $_REQUEST;
			IncludeFileModulo("DatosEmpresas","RemoteXajax","app","AdminFarmacia");
			$this->SetXajax(array("OrganizarInforPend","GuardarTemporalPen","EliminarProductosTodosV","EliminarProductos","MarcadoNullPendientes","ElimanarConsulta","GuardarTemporalProductCompletos","MostrarFormaProductosCompletaP","TransVariablesP","TransVariables"),"app_modules/AdminFarmacia/RemoteXajax/DatosEmpresas.php","ISO-8859-1");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			$centro = SessionGetVar("DatosCentro");
			$centrou=$centro['centro_utilidad'];
			$bodega = SessionGetVar("DatosBodega");
			$bodegau=$bodega['bodega'];
			$farmacia=$centro['empresa_id'];
		
		   
			
			$prefijo=$request['prefijo'];
			$numero=$request['numero'];
			$empresa=$request['empresa'];
		
			$bod = SessionGetVar("bodega");	
			$mdl = AutoCarga::factory("AdminFarmaciaSQL", "classes", "app", "AdminFarmacia");
			
			$Datos=$mdl->DetalleProductosPendientes($prefijo,$numero,$empresa);
			
			$abreviatura_estado=$Datos[0]['abreviatura'];
            if($abreviatura_estado=="")
			{
			
			  $abreviatura_estado='null';
			  
			}
			
			
			$conta=$mdl->contarDatos2($prefijo,$numero,$empresa);
			
			$contador=$conta[0]['c'];
			$conteo= $mdl->conteo;
			$pagina= $mdl-> pagina;
			$action['paginador'] = ModuloGetURL("app", "AdminFarmacia", "controller", "ListarProductosPendientes");

			$act = AutoCarga::factory("AdminFarmaciaHTML", "views", "app", "AdminFarmacia");
			$action['volver'] = ModuloGetURL("app", "AdminFarmacia", "controller", "ConsultaDocDespacho");
			$this->salida = $act->FormaPendientesProductos($action,$Datos,$request,$conteo,$pagina,$prefijo,$numero,$empresa,$farmacia,$bodegau,$contador,$centrou,$abreviatura_estado);
     		return true;
		}
	/* EMPIEZA UNICAMENTE LO DE DEVOLCION DE FARMACIA X FECHA DE VENCIMIENTO     */
	/* Funcion donde se genera el tipo de documento a eligir 
	**  @return boolean */
		
		function TipoDocumentos()
		{
			$request = $_REQUEST;
			SessionSetVar("empresa_de",$request['empresa_destino']);
			$empresa_destino = SessionGetVar("empresa_de");
			
			$centro = SessionGetVar("DatosCentro");
			$centrou=$centro['centro_utilidad'];
			$bodega = SessionGetVar("DatosBodega");
			$bodegau=$bodega['bodega'];
			$farmacia=$centro['empresa_id'];
			$centroid=$centro['centro_utilidad'];
			
			IncludeFileModulo("DatosEmpresas","RemoteXajax","app","AdminFarmacia");
			$this->SetXajax(array("TransDocid","DocumentosEstadosVerificar","Eliminardoc_tmp_id","ConsultarDoc_tmp"),"app_modules/AdminFarmacia/RemoteXajax/DatosEmpresas.php","ISO-8859-1");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			
			$mdl = AutoCarga::factory("AdminFarmaciaSQL", "classes", "app", "AdminFarmacia");
			$datos=$mdl->TiposDocumentosExistentes($farmacia,$centrou,$bodegau);
						
			$action['volver'] = ModuloGetURL("app", "AdminFarmacia", "controller", "MenuDocumentos");
			$act = AutoCarga::factory("AdminFarmaciaHTML", "views", "app", "AdminFarmacia");
			$this->salida = $act->FormaTipoDocumento($action,$datos,$farmacia,$centrou,$bodegau);
			return true;
		}
		/* Function donde se genera el documento temporal
	**  @return boolean */
		
		function DocumentoTmp()
        {
			$request=$_REQUEST;
   
			$empresa_destino = $request['empresa_destino'];
      SessionSetVar("empresa_destino",$empresa_destino);
      $empresa_destino=SessionGetVar("empresa_destino");
			SessionSetVar("document",$request['documento_id']);
			$emp = SessionGetVar("DatosFarmacia");
			$nombreFarma=$emp['descripcion1'];
			$documento_id = SessionGetVar("document");
			$centro = SessionGetVar("DatosCentro");
			$centrou=$centro['centro_utilidad'];
			$bodega = SessionGetVar("DatosBodega");
			$bodegau=$bodega['bodega'];
			$farmacia=$centro['empresa_id'];
			$Nombrecentro=$centro['descripcion'];
			$Nombrebodega=$bodega['descripcion3'];
			
			IncludeFileModulo("DatosEmpresas","RemoteXajax","app","AdminFarmacia");
			$this->SetXajax(array("TransferirDatosTmp","TranEstadosDocumento","GDoctoReal"),"app_modules/AdminFarmacia/RemoteXajax/DatosEmpresas.php","ISO-8859-1");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
						
			$mdl = AutoCarga::factory("AdminFarmaciaSQL", "classes", "app", "AdminFarmacia");
			$rst =$mdl->SelecPrefijoNumerodocumentos($documento_id,$farmacia);
			
			$tipo_doc_general_id=$rst[0]['tipo_doc_general_id'];
			$Selecoc_tmp_id=$mdl->SelecMaxdoc_tmp_id();
			$doc_tmp_id=$Selecoc_tmp_id [0]['numero'];
			$preEgre=$rst[0]['prefijo'];
			$Descripcion=$rst [0]['descripcion'];
			$numeracionEgr=$rst [0]['numeracion'];
		    
			$DocEgreso=$mdl->Consultarinv_bodegas_documentos($documento_id,$farmacia,$centrou,$bodegau);
					
			$bodegas_doc_id=$DocEgreso['0']['bodegas_doc_id'];
			$Estados =$mdl->EstadosParamestadosdocum($tipo_doc_general_id,$farmacia);
   
    //  $Ing_mto=$mdl->Registro_inv_bodegas_movimiento_tmp($doc_tmp_id,$bodegas_doc_id);
    //  $InsertEstados=$mdl->Insertar_Estados_para_documentosg($Estados,$doc_tmp_id);
		//	$ConEstados=$mdl->Consultarpara_documentosg($tipo_doc_general_id,$doc_tmp_id);
						
			$frmcontra = AutoCarga::factory("AdminFarmaciaHTML", "views", "app", "AdminFarmacia");
			$action['volver'] = ModuloGetURL("app", "AdminFarmacia", "controller", "TipoDocumentos",array("empresa_destino"=>$empresa_destino)) ;      
			
			$this->salida =$frmcontra->PintarTabla($action,$nombreFarma,$Nombrecentro,$Nombrebodega,$doc_tmp_id,$preEgre,$Descripcion,$bodegas_doc_id,$centrou,$bodegau,$tipo_doc_general_id,$Estados,$empresa_destino,$farmacia);
			return true;
		}
	/* Funcion donde se listan los productos proximos a la fecha de vencimiento  para ser devueltos
	**  @return boolean */
		
		function DevolucionFecha_vencimiento()
		{
	   		$request = $_REQUEST;
			$emp = SessionGetVar("DatosFarmacia");
			$nombreFarma=$emp['descripcion1'];
			$documento_id = SessionGetVar("document");
			$centro = SessionGetVar("DatosCentro");
			$centrou=$centro['centro_utilidad'];
			$bodega = SessionGetVar("DatosBodega");
			$bodegau=$bodega['bodega'];
			$farmacia=$centro['empresa_id'];
			$Nombrecentro=$centro['descripcion'];
			$Nombrebodega=$bodega['descripcion3'];
			
			$empresa_destino = SessionGetVar("empresa_de");
			
			if($request['doc_tmp_id'])
            SessionSetVar("Iddoc_tmp_id",$request['doc_tmp_id']);
			$doc_tmp_id=SessionGetVar("Iddoc_tmp_id");
				
			IncludeFileModulo("DatosEmpresas","RemoteXajax","app","AdminFarmacia");
			$this->SetXajax(array("ProductosSeleccionados","Eliminar_Producto_Seleccionado","ValidarDatosProducto","InsertarProductoDevol_tmp","EliminarProd_Devol_tmp","OrganizarInfo","MostrarFormaCompleta","MostrarMensaje","TransVariablesProductos","ValidarUsuario"),"app_modules/AdminFarmacia/RemoteXajax/DatosEmpresas.php","ISO-8859-1");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
			
			$mdl = AutoCarga::factory("AdminFarmaciaSQL", "classes", "app", "AdminFarmacia");
			$frmcontra = AutoCarga::factory("AdminFarmaciaHTML", "views", "app", "AdminFarmacia");
			$conteo =$pagina=0;
			if(!empty($request['buscador']))
			{
				$datos=$mdl->BuscaroListarProductoBodega($farmacia,$centrou,$bodegau,$request['buscador'],$request['offset']);
                $action['buscador']=ModuloGetURL('app','AdminFarmacia','controller','DevolucionFecha_vencimiento');
				$conteo= $mdl->conteo;
				$pagina= $mdl-> pagina;
			}
			$cdev=$mdl->BuscarTemporalDevoluc($farmacia,$centrou,$bodegau);
			$num=count($cdev);
			
		   $action['paginador'] = ModuloGetURL('app', 'AdminFarmacia', 'controller', 'DevolucionFecha_vencimiento',array("buscador"=>$request['buscador']));
           $dias_vencimiento= ModuloGetVar('app','AdminFarmacia','dias_vencimiento_product_bodega_farmacia_'.$farmacia);
			
			
			$action['InfTmp_d'] = ModuloGetURL('app', 'AdminFarmacia', 'controller', 'ListarProductosTmp_d',array("Inftmp_d"=>$Inftmp_d));
			$action['volver'] = ModuloGetURL("app", "AdminFarmacia", "controller", "DocumentoTmp",array("documento_id"=>$documento_id));
			$this->salida =$frmcontra->FormaBuscarProductos($action,$nombreFarma,$Nombrecentro,$Nombrebodega,$datos,$conteo,$pagina,$request['buscador'],$dias_vencimiento,$farmacia,$centrou,$bodegau,$cdev,$tipo_doc_general_id,$InfDocP,$bodegas_doc_id,$estadosEmpresa,$doc_tmp_id,$ConEstados,$sw_verificono,$documentos2,$num);
			return true;
		}
		/* Funcion donde Se genera el  detalle  del documento temporal
	**  @return boolean */
		
		
		function GenerarDocumentotmp()
		{
			$request = $_REQUEST;
			
			$emp = SessionGetVar("DatosFarmacia");
			$nombreFarma=$emp['descripcion1'];
			$documento_id = SessionGetVar("document");
			$centro = SessionGetVar("DatosCentro");
			$centrou=$centro['centro_utilidad'];
			$bodega = SessionGetVar("DatosBodega");
			$bodegau=$bodega['bodega'];
			$farmacia=$centro['empresa_id'];
			$Nombrecentro=$centro['descripcion'];
			$Nombrebodega=$bodega['descripcion3'];
			$documento_id = SessionGetVar("document");
			$empresa_destino = SessionGetVar("empresa_de");
			
			$doc_tmp_id=SessionGetVar("Iddoc_tmp_id");
			$documento_id = SessionGetVar("document");

			$mdl = AutoCarga::factory("AdminFarmaciaSQL", "classes", "app", "AdminFarmacia");
			$DocEgreso=$mdl->Consultarinv_bodegas_documentos($documento_id,$farmacia,$centrou,$bodegau);
			
			$bodegas_doc_id=$DocEgreso['0']['bodegas_doc_id'];
			
			$InfoTmp=$mdl->ConsultarInformacionDoc_devolucion_tmp($farmacia,$centrou,$bodegau);
			$GenerarDocTmp=$mdl->GenerarDocumentoinv_bodegas_movimiento_tmp_d($farmacia,$centrou,$bodegau,$doc_tmp_id,$InfoTmp);
			$eliminarTmp=$mdl->Eliminarv_bodegas_movimiento_tmp($farmacia,$centrou,$bodegau);
			
			$action['volver'] = ModuloGetURL("app", "AdminFarmacia", "controller", "TipoDocumentos",array("empresa_destino"=>$empresa_destino)) ;      
			$frmcontra = AutoCarga::factory("AdminFarmaciaHTML", "views", "app", "AdminFarmacia");
			$this->salida =$frmcontra->FormaMensajeGnerarDocumentoTmp($action);
			return true;
		}
	/* Function que permite Mostrar los Documentos  temporales 
	@return boolean */
		function ConsultarDtosTmp()
		{
			$request = $_REQUEST;
			$emp = SessionGetVar("DatosFarmacia");
			$nombreFarma=$emp['descripcion1'];
			$documento_id = SessionGetVar("document");
			$centro = SessionGetVar("DatosCentro");
			$centrou=$centro['centro_utilidad'];
			$bodega = SessionGetVar("DatosBodega");
			$bodegau=$bodega['bodega'];
			$farmacia=$centro['empresa_id'];
			$Nombrecentro=$centro['descripcion'];
			$Nombrebodega=$bodega['descripcion3'];
			$documento_id = SessionGetVar("document");
			$empresa_destino = SessionGetVar("empresa_de");
						
			$documento_id = SessionGetVar("document");

			
			$doc_tmp_id=$request['iditm'];
			$tipo_doc_general_id=$request['tipo_doc_general_id'];
			
			
			IncludeFileModulo("DatosEmpresas","RemoteXajax","app","AdminFarmacia");
			$this->SetXajax(array("EliminadocTmp_d","TrasnpoVerifEstados","TrasnpoDocumGenerarE"),"app_modules/AdminFarmacia/RemoteXajax/DatosEmpresas.php","ISO-8859-1");
			$this->IncludeJS("CrossBrowser");
			$this->IncludeJS("CrossBrowserEvent");
			$this->IncludeJS("CrossBrowserDrag");
		
			$mdl = AutoCarga::factory("AdminFarmaciaSQL", "classes", "app", "AdminFarmacia");
			$datos=$mdl->ConsularInformaciontmpC($doc_tmp_id);
			$ConEstados=$mdl->Consultarpara_documentosg($tipo_doc_general_id,$doc_tmp_id);
		
			$action['volver'] = ModuloGetURL("app", "AdminFarmacia", "controller", "TipoDocumentos",array("empresa_destino"=>$empresa_destino)) ;      
			$frmcontra = AutoCarga::factory("AdminFarmaciaHTML", "views", "app", "AdminFarmacia");
			$this->salida =$frmcontra->formaListarDocumentoTemp_d($action,$datos,$ConEstados,$tipo_doc_general_id,$doc_tmp_id);
			return true;
		}
		
	/* Funcion donde Se genera el  documento real
	**  @return boolean */
		
		function GenerarDocumentoRealE()
		{
		
			$request = $_REQUEST;
			$emp = SessionGetVar("DatosFarmacia");
			$nombreFarma=$emp['descripcion1'];
			$documento_id = SessionGetVar("document");
			$centro = SessionGetVar("DatosCentro");
			$centrou=$centro['centro_utilidad'];
			$bodega = SessionGetVar("DatosBodega");
			$bodegau=$bodega['bodega'];
			$farmacia=$centro['empresa_id'];
			$Nombrecentro=$centro['descripcion'];
			$Nombrebodega=$bodega['descripcion3'];
			$documento_id = SessionGetVar("document");
			
            
			$doc_tmp_id=$request['doc_tmp_id'];
			
			$mdl = AutoCarga::factory("AdminFarmaciaSQL", "classes", "app", "AdminFarmacia");
			$rst =$mdl->SelecPrefijoNumerodocumentos($documento_id,$farmacia);
			$prefijo=$rst[0]['prefijo'];
			$numero=$rst[0]['numeracion'];
		
			$DocEgreso=$mdl->Consultarinv_bodegas_documentos($documento_id,$farmacia,$centrou,$bodegau);
   			$bodegas_doc_id=$DocEgreso[0]['bodegas_doc_id'];
			$Doc_tmp=$mdl->ConsultarInformacionBodegaTmp($bodegas_doc_id,$doc_tmp_id);
   
			$observacion=$Doc_tmp[0]['observacion'];
			$estado_abreviatura=$Doc_tmp[0]['abreviatura'];
			$empresa_destino=$Doc_tmp[0]['empresa_destino'];
       
			$Ing=$mdl->GenerarDocumentoDevolucionReal($documento_id,$farmacia,$centrou,$bodegau,$prefijo,$numero,$observacion,$estado_abreviatura,$empresa_destino);
			$inf=$mdl->ConsularInformaciontmpC($doc_tmp_id);
			
    
			$GeneralReal_d=$mdl->GenerarDocInv_bodegas_movimiento_d($farmacia,$prefijo,$numero,$centrou,$bodegau,$inf);
			
						
		    $EliminarInformacion=$mdl->Delete_bodegas_movimiento_tmp($bodegas_doc_id,$doc_tmp_id);
		    $EliminarInformacionTmp_d=$mdl->Delete_bodegas_movimiento_tmp_d($doc_tmp_id,$farmacia,$centrou,$bodegau);
			$TraerDocumentoGenero=$mdl->MostrarNumeracionDocumento($documento_id,$farmacia,$centrou,$bodegau,$prefijo);
			$numeroGenerado=$TraerDocumentoGenero [0]['movimiento'];
			$action['volver'] = ModuloGetURL("app", "AdminFarmacia", "controller", "TipoDocumentos",array("empresa_destino"=>$empresa_destino)) ;      
			$frmcontra = AutoCarga::factory("AdminFarmaciaHTML", "views", "app", "AdminFarmacia");
			$this->salida =$frmcontra->FormaMensajeGenerarDocReal($action,$numeroGenerado,$prefijo,$farmacia);
			return true;
		}
	/*
		*funcion que permite buscar un producto en otras bodegas de la farmacia o
		* en otras farmacias
	*/
		function BusquedaProductosFarmacias()
		{
			$request = $_REQUEST;
			
		    $emp = SessionGetVar("DatosFarmacia");
			$nombreFarma=$emp['descripcion1'];
			$documento_id = SessionGetVar("document");
			$centro = SessionGetVar("DatosCentro");
			$centrou=$centro['centro_utilidad'];
			$bodega = SessionGetVar("DatosBodega");
			$bodegau=$bodega['bodega'];
			$farmacia=$centro['empresa_id'];
			$Nombrecentro=$centro['descripcion'];
			$Nombrebodega=$bodega['descripcion3'];
			$documento_id = SessionGetVar("document");
			
			$mdl = AutoCarga::factory("AdminFarmaciaSQL", "classes", "app", "Consulta_Productos");
				$conteo =$pagina=0;
				if(!empty($request['buscador']))
				{
					$datos=$mdl->BuscarProducPorFarmacia($bodegau,$request['buscador'],$request['offset']);
					$action['buscador']=ModuloGetURL('app','Consulta_Productos','controller','BusquedaProductosFarmacias');
					$conteo= $mdl->conteo;
					$pagina= $mdl-> pagina;
				}
							
			$action['paginador'] = ModuloGetURL('app', 'Consulta_Productos', 'controller', 'BusquedaProductosFarmacias',array("buscador"=>$request['buscador'],"far"=>$far,"Centrid"=>$Centrid,"bod"=>$bod));
			$action['volver'] = ModuloGetURL("app", "Consulta_Productos", "controller", "MenuPrincipal",array("AdminFarmacia"=>$request['AdminFarmacia']));
			$frmcontra = AutoCarga::factory("AdminFarmaciaHTML", "views", "app", "Consulta_Productos");
			$this->salida =$frmcontra->FormaBuscarProductosFarmacia($action,$datos,$conteo,$pagina,$request['buscador']);
			return true;
		}
		
	
	} 
?>