<?php
	/**
	* @package IPSOFT-SIIS
	* @version $Id: app__controller.php,v 1.0
	* @copyright (C) 2010 IPSOFT - SA (www.ipsoft-sa.com)
	* @author Sandra Viviana Pantoja Torres 
	*/
	/**
	* Clase Control: AdministracionFarmacia
	* Clase encargada del control de llamado de metodos en el modulo
	*
	* @package IPSOFT-SIIS
	/*/
	class app_CajaGeneralEmpresa_controller  extends classModulo
	{
	/**
		* Constructor de la clase
	*/
	function app_CajaGeneralEmpresa_controller()
	{}
	/**
        *  Funcion principal del modulo
        *  @return boolean
    */
		function Main()
		{
  			$request = $_REQUEST;
        SessionSetVar("tipoid",$request['tipoid']);
        $tipoid = SessionGetVar("tipoid");
        SessionSetVar("id",$request['id']);
        $id = SessionGetVar("id");
        SessionSetVar("document",$request['document']);
        $documento = SessionGetVar("document");
        SessionSetVar("farmacia",$request['farmacia']);
        $farmacia = SessionGetVar("farmacia");
        SessionSetVar("prefijo",$request['prefijo']);
        $prefijodoc = SessionGetVar("prefijo");
        SessionSetVar("numero",$request['numero']);
        $numeracion = SessionGetVar("numero");
        SessionSetVar("centro",$request['centro']);
        $centro_utilidad = SessionGetVar("centro");
        SessionSetVar("bodega",$request['bodega']);
        $bodega = SessionGetVar("bodega");
  			$CajaE = AutoCarga::factory('CajaGeneralEmpresaSQL', '', 'app', 'CajaGeneralEmpresa');
  			$permisos = $CajaE->BuscarPermisosUser();    
  			$ttl_gral = "CAJA GENERAL EMPRESA";
  			$mtz[0]='EMPRESA';
  			$url[0] = 'app';
  			$url[1] = 'CajaGeneralEmpresa'; 
  			$url[2] = 'controller';
  			$url[3] = 'Menu'; 
  			$url[4] = 'CajaGeneralEmpresa'; 
  			$action['volver'] = ModuloGetURL('system', 'Menu');
  			$this->salida = gui_theme_menu_acceso($ttl_gral, $mtz, $permisos, $url, $action['volver']);
  			return true;
		}    
 	/*
		* Funcion de control para el Menu Inicial
		 *  @return boolean
	*/
	  function Menu()
  	{
        $request = $_REQUEST;
        SessionSetVar("caja_id",$request['CajaGeneralEmpresa']['caja_id']);
        $caja_id = SessionGetVar("caja_id");
        $tipoid = SessionGetVar("tipoid");
        $id = SessionGetVar("id");
        $documento = SessionGetVar("document");
        $farmacia = SessionGetVar("farmacia");
        $prefijodoc = SessionGetVar("prefijo");
        $numeracion = SessionGetVar("numero");
        $centro_utilidad = SessionGetVar("centro");
        $bodega = SessionGetVar("bodega");
        IncludeFileModulo("CajaEmpresa","RemoteXajax","app","CajaGeneralEmpresa");
        $this->SetXajax(array("TrasInformaPago","PagoEnEfectivo","PagoEfectivoCompleto","TipoPagoCheque","TarjetaDebito","TarjetaCredito"),"app_modules/CajaGeneralEmpresa/RemoteXajax/CajaEmpresa.php","ISO-8859-1");
        $this->IncludeJS("CrossBrowser");
        $this->IncludeJS("CrossBrowserEvent");
        $this->IncludeJS("CrossBrowserDrag");
        $Recibocaja_id= ModuloGetVar('app','CajaGeneralEmpresa','documento_id_cajageneral_'.$farmacia);
        $cajafact_id= ModuloGetVar('app','CajaGeneralEmpresa','documento_id_recibocajafacturado_'.$farmacia);
        $CajaE = AutoCarga::factory('CajaGeneralEmpresaSQL', 'classes', 'app', 'CajaGeneralEmpresa');
        $Tercero= $CajaE->DatosBasicosTercero($tipoid,$id);
        $datos= $CajaE->consultarInformacionDocumentoReal($farmacia,$prefijodoc,$numeracion);
        $action['volver'] = ModuloGetURL("app", "CajaGeneralEmpresa", "controller", "Menu") ;      
        $CajaV = AutoCarga::factory("CajaGeneralEmpresaHTML", "views", "app", "CajaGeneralEmpresa");
        $this->salida = $CajaV->FormaOrdenesServicio($Tercero,$action,$datos,$Recibocaja_id,$cajafact_id);
        return true;
    }
	/*
		 * Funcion de control para Pago En Efectivo
		 *  @return boolean
	*/
		Function PagoEfectivoCompleto2()
		{
		
			$request = $_REQUEST;
      $tipoid = SessionGetVar("tipoid");
      $id = SessionGetVar("id");
      $farmacia = SessionGetVar("farmacia");
      $documento = SessionGetVar("document");
    
      SessionSetVar("valorconDescuento",$request['valorconDescuento']);
      $efectivo = SessionGetVar("valorconDescuento");
      $centro_utilidad = SessionGetVar("centro");
      $prefijodoc = SessionGetVar("prefijo");
      $numeracion = SessionGetVar("numero");
      $bodega = SessionGetVar("bodega");
      $caja_id = SessionGetVar("caja_id");
	    $Recibocaja_id= ModuloGetVar('app','CajaGeneralEmpresa','documento_id_cajageneral_'.$farmacia);
    
			$cajafact_id= ModuloGetVar('app','CajaGeneralEmpresa','documento_id_recibocajafacturado_'.$farmacia);
  		$CajaE = AutoCarga::factory('CajaGeneralEmpresaSQL', 'classes', 'app', 'CajaGeneralEmpresa');
			$dat= $CajaE->ConsultarPrefijoReciboCaja($documento);
			$prefijo=$dat[0]['prefijo'];
			$dt= $CajaE->ConsultarPrefijoReciboCaja($documento);
			$prefijoF=$dt[0]['prefijo'];
			$numeroF=$dt[0]['numeracion'];
			$cheque=0;
			$tarjeta=0;
			$total_pago=$efectivo+$cheque+$tarjeta;
			$datos= $CajaE->InsertarRecibocaja($farmacia,$prefijo,$total_pago,$efectivo,$cheque,$tarjeta,$tipoid,$id,$documento,$prefijodoc,$numeracion,$caja_id,$Recibocaja_id);
			$reciboid= $CajaE->Seleccionarcaja();
			$recibid=$reciboid[0]['reciboid'];
		  $infac=$CajaE->InsertarFacturaContado($farmacia,$centro_utilidad,$numeroF,$prefijoF,$total_pago,$efectivo,$cheque,$tarjeta,$tipoid,$id,$caja_id,$cajafact_id,$recibid);	
			$CajaV = AutoCarga::factory("CajaGeneralEmpresaHTML", "views", "app", "CajaGeneralEmpresa");
      $this->salida = $CajaV->FormaMensaje($prefijoF,$numeroF,$farmacia,$prefijodoc,$numeracion,$recibid,$centro_utilidad,$tipoid,$id,$bodega);
      return true;
		}
  /*
		 * Funcion de control para Pago En Cheque
		 *  @return boolean
	*/
		function PagoChequeCompleto()
		{
        $request = $_REQUEST;
        $tipoid = SessionGetVar("tipoid");
        $id = SessionGetVar("id");
        $farmacia = SessionGetVar("farmacia");
        $documento = SessionGetVar("document");
        $cheque=$request['valorconDescuento'];
        $prefijodoc = SessionGetVar("prefijo");
        $numeracion = SessionGetVar("numero");
        $bodega = SessionGetVar("bodega");
        $caja_id = SessionGetVar("caja_id");
        SessionSetVar("valorconDescuento",$request['valorconDescuento']);
        $Cheque = SessionGetVar("valorconDescuento");
    		$CajaE = AutoCarga::factory('CajaGeneralEmpresaSQL', 'classes', 'app', 'CajaGeneralEmpresa');
    		$dat= $CajaE->ComboEntidadConfirma();
    		$centro_utilidad = SessionGetVar("centro");
    		$action['confi'] = ModuloGetURL("app", "CajaGeneralEmpresa", "controller", "IngresarInformacionCheque");  
    		$action['volver'] = ModuloGetURL("app", "CajaGeneralEmpresa", "controller", "Main", array("tipoid"=>$tipoid,"id"=>$id,"document"=>$documento,"farmacia"=>$farmacia,"prefijo"=>$prefijodoc,"numero"=>$numeracion,"centro"=>$centro_utilidad));
    		$CajaV = AutoCarga::factory("CajaGeneralEmpresaHTML", "views", "app", "CajaGeneralEmpresa");
        $this->salida = $CajaV->FormaCheques($action,$dat);
         return true;
	  }
  /*
		 * Funcion de control para Pago En Cheque
		 *  @return boolean
	*/
	
	 function IngresarInformacionCheque()
	{
    	$request = $_REQUEST;
		  $tipoid = SessionGetVar("tipoid");
      $id = SessionGetVar("id");
      $farmacia = SessionGetVar("farmacia");
      $documento = SessionGetVar("document");
      $cheque=$request['valorconDescuento'];
      $prefijodoc = SessionGetVar("prefijo");
      $numeracion = SessionGetVar("numero");
      $entconfirma=$request ['entconfirma'];
      $funconfirma=$request['funconfirma'];
      $numconfirma=$request['numconfirma'];
      $fechaconfirma=$request['fechaconfirma'];
      $Cheque = SessionGetVar("valorconDescuento");
      $bodega = SessionGetVar("bodega");
      $centro_utilidad = SessionGetVar("centro");
      $caja_id = SessionGetVar("caja_id");
		  $CajaE = AutoCarga::factory('CajaGeneralEmpresaSQL', 'classes', 'app', 'CajaGeneralEmpresa');
		  $dat= $CajaE->IngresarConfirmacionCheque($entconfirma,$funconfirma,$numconfirma,$fechaconfirma);
		  $Bancos= $CajaE->ComboEntidadConfirma();
     	$Tercero= $CajaE->DatosBasicosTercero($tipoid,$id);
		  $action['guardar'] = ModuloGetURL("app", "CajaGeneralEmpresa", "controller", "IngresarInformacionChe");  
		  $action['volver'] = ModuloGetURL("app", "CajaGeneralEmpresa", "controller", "Main", array("tipoid"=>$tipoid,"id"=>$id,"document"=>$documento,"farmacia"=>$farmacia,"prefijo"=>$prefijodoc,"numero"=>$numeracion,"centro"=>$centro_utilidad));
	  	$CajaV = AutoCarga::factory("CajaGeneralEmpresaHTML", "views", "app", "CajaGeneralEmpresa");
      $this->salida = $CajaV->FormaChequesInformacion($action,$Bancos,$Tercero,$Cheque);
	   	return true;
    }
  /*
		 * Funcion para Ingresar la Informacion del pago en cheque
		 *  @return boolean
	*/
	  function IngresarInformacionChe()
	  {
			$request = $_REQUEST;
      $tipoid = SessionGetVar("tipoid");
      $id = SessionGetVar("id");
      $farmacia = SessionGetVar("farmacia");
      $documento = SessionGetVar("document");
      $Cheque = SessionGetVar("valorconDescuento");
      $nocheque=$request['nocheque'];   
      $banco=$request['banco'];   
      $ctac=$request['ctac'];
      $girador=$request['girador'];
      $fechacheque=$request['fechacheque'];
      $fech=$request['fech'];
      $totalc=$request['totalc'];
      $caja_id = SessionGetVar("caja_id");
      $centro_utilidad = SessionGetVar("centro");
      $bodega = SessionGetVar("bodega");
			$CajaE = AutoCarga::factory('CajaGeneralEmpresaSQL', 'classes', 'app', 'CajaGeneralEmpresa');
			$max= $CajaE->SeleccionarMaxtmp_confirmacion_che();
			$mov=$max[0]['numero'];
			$prefijodoc = SessionGetVar("prefijo");
	    $numeracion = SessionGetVar("numero");
	    $Recibocaja_id= ModuloGetVar('app','CajaGeneralEmpresa','documento_id_cajageneral_'.$farmacia);
			$cajafact_id= ModuloGetVar('app','CajaGeneralEmpresa','documento_id_recibocajafacturado_'.$farmacia);
			$CajaE = AutoCarga::factory('CajaGeneralEmpresaSQL', 'classes', 'app', 'CajaGeneralEmpresa');
			$dat= $CajaE->ConsultarPrefijoReciboCaja($Recibocaja_id);
			$prefijo=$dat[0]['prefijo'];
			$dt= $CajaE->ConsultarPrefijoReciboCaja($cajafact_id);
			$prefijoF=$dt[0]['prefijo'];
			$numeroF=$dt[0]['numeracion'];
			$efectivo=0;
			$tarjeta=0;
			$total_pago=$efectivo+$Cheque+$tarjeta;
			$datos= $CajaE->InsertarRecibocaja($farmacia,$prefijo,$total_pago,$efectivo,$Cheque,$tarjeta,$tipoid,$id,$documento,$prefijodoc,$numeracion,$caja_id,$Recibocaja_id);
			$reciboid= $CajaE->Seleccionarcaja();
			$recibid=$reciboid[0]['reciboid'];
			$indinf= $CajaE->IngresarMovimientoCheque($farmacia,$banco,$nocheque,$girador,$fechacheque,$totalc,$fech,$ctac,$recibid,$prefijo,$centro_utilidad);
			$dach=$CajaE->seleccionarInformacionConfirmacion($mov);
			$entidad_confirma=$dach[0]['entidad_confirma'];
			$funcionario_confirma=$dach[0]['funcionario_confirma'];
			$numero_confirmacion=$dach[0]['numero_confirmacion'];
			$ingrsarDatod=$CajaE->ingresarMovimientoChequeR($mov,$entidad_confirma,$funcionario_confirma,$numero_confirmacion);
			$eli=$CajaE->EliminarTemp($mov);
      $infac=$CajaE->InsertarFacturaContado($farmacia,$centro_utilidad,$numeroF,$prefijoF,$total_pago,$efectivo,$Cheque,$tarjeta,$tipoid,$id,$caja_id,$documento,$cajafact_id,$recibid);	
			$CajaV = AutoCarga::factory("CajaGeneralEmpresaHTML", "views", "app", "CajaGeneralEmpresa");
			$this->salida = $CajaV->FormaMensaje($prefijoF,$numeroF,$farmacia,$prefijodoc,$numeracion,$recibid,$centro_utilidad,$tipoid,$id,$bodega);
			return true;
	  }	
  /*
		 * Funcion para pago con tarjeta debito
		 *  @return boolean
	*/
		function PagoConTarjetaDebito()
		{
	      $request = $_REQUEST;
        $tipoid = SessionGetVar("tipoid");
        $id = SessionGetVar("id");
        $farmacia = SessionGetVar("farmacia");
        $documento = SessionGetVar("document");
        $centro_utilidad = SessionGetVar("centro");
        $prefijodoc = SessionGetVar("prefijo");
        $numeracion = SessionGetVar("numero");
        $bodega = SessionGetVar("bodega");
        $caja_id = SessionGetVar("caja_id");
        SessionSetVar("valorconDescuento",$request['valorconDescuento']);
        $tarjeta = SessionGetVar("valorconDescuento");
        $CajaE = AutoCarga::factory('CajaGeneralEmpresaSQL', 'classes', 'app', 'CajaGeneralEmpresa');
        $Tercero= $CajaE->DatosBasicosTercero($tipoid,$id);
        $datos=$CajaE->SeleccionarTarjeta();
        $action['guardar'] = ModuloGetURL("app", "CajaGeneralEmpresa", "controller", "IngresarInformacionTarjetaDebito");  
        $action['volver'] = ModuloGetURL("app", "CajaGeneralEmpresa", "controller", "Main", array("tipoid"=>$tipoid,"id"=>$id,"document"=>$documento,"farmacia"=>$farmacia,"prefijo"=>$prefijodoc,"numero"=>$numeracion,"centro"=>$centro_utilidad));
        $CajaV = AutoCarga::factory("CajaGeneralEmpresaHTML", "views", "app", "CajaGeneralEmpresa");
        $this->salida = $CajaV->FormaTarjetaDebito($action,$Tercero,$datos,$tarjeta);
        return true;
		}
  /*
		 * Funcion para Informar el  pago con tarjeta debito
		 *  @return boolean
	*/
	
		function IngresarInformacionTarjetaDebito()
		{
			$request = $_REQUEST;
      $tipoid = SessionGetVar("tipoid");
      $id = SessionGetVar("id");
      $farmacia = SessionGetVar("farmacia");
      $documento = SessionGetVar("document");
      $tarjeta = SessionGetVar("valorconDescuento");
      $tarjetat=$request['tarjeta']; 
      $numtarjeta=$request['numtarjeta']; 		
      $noautorizad=$request['noautorizad']; 
      $centro_utilidad = SessionGetVar("centro");
      $bodega = SessionGetVar("bodega");
      $CajaE = AutoCarga::factory('CajaGeneralEmpresaSQL', 'classes', 'app', 'CajaGeneralEmpresa');
      $prefijodoc = SessionGetVar("prefijo");
      $numeracion = SessionGetVar("numero");
      $caja_id = SessionGetVar("caja_id");
			$Recibocaja_id= ModuloGetVar('app','CajaGeneralEmpresa','documento_id_cajageneral_'.$farmacia);
			$cajafact_id= ModuloGetVar('app','CajaGeneralEmpresa','documento_id_recibocajafacturado_'.$farmacia);
			$CajaE = AutoCarga::factory('CajaGeneralEmpresaSQL', 'classes', 'app', 'CajaGeneralEmpresa');
			$dat= $CajaE->ConsultarPrefijoReciboCaja($Recibocaja_id);
			$prefijo=$dat[0]['prefijo'];
			$dt= $CajaE->ConsultarPrefijoReciboCaja($cajafact_id);
			$prefijoF=$dt[0]['prefijo'];
			$numeroF=$dt[0]['numeracion'];
			$efectivo=0;
			$Cheque=0;
			$total_pago=$efectivo+$Cheque+$tarjeta;
			$datos= $CajaE->InsertarRecibocaja($farmacia,$prefijo,$total_pago,$efectivo,$Cheque,$tarjeta,$tipoid,$id,$documento,$prefijodoc,$numeracion,$caja_id,$Recibocaja_id);
			$reciboid= $CajaE->Seleccionarcaja();
			$recibid=$reciboid[0]['reciboid'];
			$indinf= $CajaE->InsertarTarjetaDebito($farmacia,$centro_utilidad,$recibid,$prefijo,$noautorizad,$tarjetat,$numtarjeta,$tarjeta);
      $infac=$CajaE->InsertarFacturaContado($farmacia,$centro_utilidad,$numeroF,$prefijoF,$total_pago,$efectivo,$Cheque,$tarjeta,$tipoid,$id,$caja_id,$cajafact_id,$recibid);	
			$CajaV = AutoCarga::factory("CajaGeneralEmpresaHTML", "views", "app", "CajaGeneralEmpresa");
			$this->salida = $CajaV->FormaMensaje($prefijoF,$numeroF,$farmacia,$prefijodoc,$numeracion,$recibid,$centro_utilidad,$tipoid,$id,$bodega);
			return true;
		}
  /*
		 * Funcion para el  pago con tarjeta Credito
		 *  @return boolean
	*/
	
		function PagoConTarjetaCredito()
		{
	
      $request = $_REQUEST;
      $tipoid = SessionGetVar("tipoid");
      $id = SessionGetVar("id");
      $farmacia = SessionGetVar("farmacia");
      $documento = SessionGetVar("document");
      $bodega = SessionGetVar("bodega");
      $prefijodoc = SessionGetVar("prefijo");
      $numeracion = SessionGetVar("numero");
      SessionSetVar("valorconDescuento",$request['valorconDescuento']);
      $tarjeta = SessionGetVar("valorconDescuento");
      $CajaE = AutoCarga::factory('CajaGeneralEmpresaSQL', 'classes', 'app', 'CajaGeneralEmpresa');
      $Tercero= $CajaE->DatosBasicosTercero($tipoid,$id);
      $datos=$CajaE->SeleccionarTarjeta();
      $caja_id = SessionGetVar("caja_id");
      $centro_utilidad = SessionGetVar("centro");
			$action['guardar'] = ModuloGetURL("app", "CajaGeneralEmpresa", "controller", "IngresarInformacionTarjetaCredito");  
			$action['volver'] = ModuloGetURL("app", "CajaGeneralEmpresa", "controller", "Main", array("tipoid"=>$tipoid,"id"=>$id,"document"=>$documento,"farmacia"=>$farmacia,"prefijo"=>$prefijodoc,"numero"=>$numeracion,"centro"=>$centro_utilidad));
			$CajaV = AutoCarga::factory("CajaGeneralEmpresaHTML", "views", "app", "CajaGeneralEmpresa");
	    $this->salida = $CajaV->FormaTarjetaCredito($action,$Tercero,$datos,$tarjeta);
		    return true;
		
		}
  /*
		 * Funcion para Informar el  pago con tarjeta credito
		 *  @return boolean
	*/
	
		function IngresarInformacionTarjetaCredito()
		{
      $request = $_REQUEST;
      $tipoid = SessionGetVar("tipoid");
      $id = SessionGetVar("id");
      $farmacia = SessionGetVar("farmacia");
      $documento = SessionGetVar("document");
      $tarjeta = SessionGetVar("valorconDescuento");
      $bodega = SessionGetVar("bodega");
      $tarjetat=$request['tarjeta']; 
      $numtarjeta=$request['numtarjeta']; 		
      $noautorizad=$request['noautorizad']; 
      $socio=$request['socio']; 		
      $fechaexp=$request['fechaexp']; 
      $autoriza=$request['autoriza']; 		
      $fecha=$request['fecha']; 
      $centro_utilidad = SessionGetVar("centro");
      $CajaE = AutoCarga::factory('CajaGeneralEmpresaSQL', 'classes', 'app', 'CajaGeneralEmpresa');
      $prefijodoc = SessionGetVar("prefijo");
      $numeracion = SessionGetVar("numero");
      $Recibocaja_id= ModuloGetVar('app','CajaGeneralEmpresa','documento_id_cajageneral_'.$farmacia);
      $cajafact_id= ModuloGetVar('app','CajaGeneralEmpresa','documento_id_recibocajafacturado_'.$farmacia);
      $caja_id = SessionGetVar("caja_id");
			$CajaE = AutoCarga::factory('CajaGeneralEmpresaSQL', 'classes', 'app', 'CajaGeneralEmpresa');
			$dat= $CajaE->ConsultarPrefijoReciboCaja($Recibocaja_id);
			$prefijo=$dat[0]['prefijo'];
			$dt= $CajaE->ConsultarPrefijoReciboCaja($cajafact_id);
			$prefijoF=$dt[0]['prefijo'];
			$numeroF=$dt[0]['numeracion'];
			$efectivo=0;
			$Cheque=0;
			$total_pago=$efectivo+$Cheque+$tarjeta;
			$datos= $CajaE->InsertarRecibocaja($farmacia,$prefijo,$total_pago,$efectivo,$Cheque,$tarjeta,$tipoid,$id,$documento,$prefijodoc,$numeracion,$caja_id,$Recibocaja_id);
			$reciboid= $CajaE->Seleccionarcaja();
			$recibid=$reciboid[0]['reciboid'];
			$indinf= $CajaE->InsertarTarjetaCredito($tarjetat,$farmacia,$centro_utilidad,$recibid,$prefijo,$noautorizad,$socio,$autoriza,$tarjeta,$numtarjeta,$fechaexp,$fecha);
      $infac=$CajaE->InsertarFacturaContado($farmacia,$centro_utilidad,$numeroF,$prefijoF,$total_pago,$efectivo,$Cheque,$tarjeta,$tipoid,$id,$caja_id,$cajafact_id,$recibid);
			$CajaV = AutoCarga::factory("CajaGeneralEmpresaHTML", "views", "app", "CajaGeneralEmpresa");
			$this->salida = $CajaV->FormaMensaje($prefijoF,$numeroF,$farmacia,$prefijodoc,$numeracion,$recibid,$centro_utilidad,$tipoid,$id,$bodega);
		 	return true;
		}
	
	} 
?>