<?php
	
	function ActualizarTraslado($Cuenta)
	{
		$objResponse = new xajaxResponse();  
          
          list($dbconnect) = GetDBconn();
     	$query = "UPDATE estaciones_enfermeria_ingresos_pendientes
                    SET sw_traslado_medicamentos = '0'
                    WHERE numerodecuenta = ".$Cuenta.";";
          $dbconnect->Execute($query);
          
          $objResponse->Call("load_page");
          return $objResponse;
	}
	
?>