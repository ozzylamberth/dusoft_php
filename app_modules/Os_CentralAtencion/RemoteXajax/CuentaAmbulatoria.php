<?php
	
	function CrearCuentaOrdenServicio($orden, $cuenta)
  	{
  	//jab--Query para cargar nombre del profesional
	list($dbconn) = GetDBconn();
	$sql = "insert into orden_cuenta_profesional
	(numero_orden_id,numerodecuenta) values ($orden,$cuenta)";
										
        $dbconn->Execute($sql);

            if($dbconn->ErrorNo() != 0)
            {
                $this->error = "[" . get_class($this) . "][" . __LINE__ . "]".$sql;
                $this->mensajeDeError = $dbconn->ErrorMsg();
                $dbconn->RollbackTrans();
                return false;
            }
	    return true;
  	}

	function CrearCuentaAmbulatoria($orden,$departamento,$plan, $cuenta = null,$autorizacion)
	{
		$objResponse = new xajaxResponse();
		IncludeClass('Cuentas');
		IncludeClass('ConsultaAtencionOs','','app','Os_CentralAtencion');
		$cnt = new Cuentas();
		$cna = new ConsultaAtencionOs();
		
		$html = ""; 
	
		$ordenes = $cna->ObtenerDatosOs($orden);
		$ordenes['autorizacion_int'] = $autorizacion;
		$ordenes['departamento'] = $departamento;
		$ordenes['plan_id'] = $plan;
		//
               SessionDelVar('CuentaAmbulatoria');
		//
		if($cuenta === null || $cuenta == "undefined" || $cuenta == "")
		{
			
			
			$cuenta = $cnt->CrearCuentaAmbulatoria($ordenes);
			
			if($cuenta === false)
				$html = $cnt->Err().$cnt->ErrMsg();
			else
			{
				//
				SessionSetVar('CuentaAmbulatoria',$cuenta);
				//
				$rst = $cnt->CargarOScargos($cuenta,$departamento,SessionGetVar("CargosSel"));
				if($rst === false)
					$html = $cnt->Err().$cnt->ErrMsg();
			}
		}
		else
		{
			
			$rst = $cnt->CargarOScargos($cuenta,$departamento,SessionGetVar("CargosSel"));
			if($rst === false)
				$html = $cnt->Err().$cnt->ErrMsg();
				
		}
		$var=CrearCuentaOrdenServicio($orden, $cuenta);
		//$html = utf8_encode($html);
		
		if($html == "")
			$objResponse->call("RecargarPagina");
			
		else
			$objResponse->assign("error","innerHTML",$html);
		
		SessionSetVar("NumeroCuentaSeleccionada",$cuenta);
		
		return $objResponse;
		
	}
	
?>