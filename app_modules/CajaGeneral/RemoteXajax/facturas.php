<?php





function sincronizar_facturas_pendientes_ws_fi($empresa, $prefijo, $factura){
      $dusoft_fi = AutoCarga::factory("SincronizacionDusoftFI", "SincronizacionDusoftFI", "", "");
          
          
          $resultado_sincronizacion_ws = $dusoft_fi->facturas_talonario_fi($_SESSION['CAJA']['EMPRESA'], $prefijo, $factura);
        
        
        //echo print_r($resultado_sincronizacion_ws);
        
        
          $objResponse = new xajaxResponse();
          $url =  ModuloGetURL('app', 'CajaGeneral', 'user', 'LlamaFormaBuscar', array('empresa' => $empresa, "PrefijoFac" => $prefijo, "Factura" => $factura));
           $msj = "";
          if($resultado_sincronizacion_ws['resultado_ws']){
              $msj = "Factura Numero: {$prefijo}{$factura}";
          }
          
           $objResponse->alert("{$resultado_sincronizacion_ws["mensaje_ws"]} \n {$msj}");
          $objResponse->script("window.location='{$url}';");
           return $objResponse;
}
