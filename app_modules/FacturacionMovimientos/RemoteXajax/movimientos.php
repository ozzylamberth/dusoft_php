<?php
	function MarcarFactura($prefijo,$factura)
	{
		$html = "";
		$facturas = SessionGetVar("FacturasSeleccionadas");
		$objResponse = new xajaxResponse();
		if(!empty($facturas[$prefijo][$factura]))
		{
			unset($facturas[$prefijo][$factura]);
			if(empty($facturas[$prefijo])) unset($facturas[$prefijo]);
			if(empty($facturas)) 
			{
				unset($facturas);
				$objResponse->assign("asignar","style.display","none");
			}
			$html .= "							<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\">\n";
		}
		else
		{
			$facturas[$prefijo][$factura]['prefijo'] = $prefijo;
			$facturas[$prefijo][$factura]['factura_fiscal'] = $factura;
			$html .= "							<img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\" width=\"17\" height=\"17\">\n";
		}
		
		SessionSetVar("FacturasSeleccionadas",$facturas);
		$objResponse->assign($prefijo.$factura,"innerHTML",$html);
		return $objResponse;
	}
	
	function EvaluarFacturasAsignadas()
	{
		$objResponse = new xajaxResponse();
		$facturas = SessionGetVar("FacturasSeleccionadas");
		if(empty($facturas))
		{
			$html = "<label class=\"label_error\">NO SE HA SELECCIONADO NINGUNA FACTURA PARA SER ASIGNADA</label>";
			$objResponse->assign("error","innerHTML",$html);
		}
		else
		{
			$objResponse->assign("error","innerHTML",$html);
			$objResponse->assign("asignar","style.display","block");
		}
		return $objResponse;
	}
	
	function AgregarUsuariosGrupo($grupoid,$empresa,$usuario)
	{
		IncludeClass('Movimientos','','app','FacturacionMovimientos');
		$mvs = new Movimientos();
		
		$grupo = $mvs->ObtenerGrupos($empresa,null,$grupoid);
		$usuarios = $mvs->ObtenerUsuariosGrupos($grupoid,$empresa,$usuario);
		
		$key = key($grupo);
		
		//$html  = "document.asignacion.sw_estado.value = ".$grupo[$key]['sw_estado']." ;\n";
		$html  = "document.asignacion.usuario_seleccion.options.length = 0 ;\n";
		$html .= "document.asignacion.usuario_seleccion.options[0] = new Option('--SELECCIONAR--','',false, false);\n";
		
		foreach($usuarios as $key => $user)
			$html .= "document.asignacion.usuario_seleccion.options[".($key+1)."] = new Option('".$user['nombre']."','".$user['usuario_id']."',false, false);\n";
		
		$objResponse = new xajaxResponse();
		$objResponse->script($html);
		return $objResponse;
	}
	
	function EvaluarFacturasConfirmar()
	{
		$objResponse = new xajaxResponse();
		$facturas = SessionGetVar("FacturasSeleccionadas");
		if(empty($facturas))
		{
			$html = "<label class=\"label_error\">NO SE HA SELECCIONADO NINGUNA FACTURA PARA SU CONFIRMACION</label>";
			$objResponse->assign("error","innerHTML",$html);
		}
		else
		{
			$html = "";
			$facturas = SessionGetVar("FacturasSeleccionadas");
			$empresas = SessionGetVar('MovimientosEmpresa');
			$grupos = SessionGetVar('MovimientosGrupos');
			
			IncludeClass('Movimientos','','app','FacturacionMovimientos');
			$mvs = new Movimientos();
			$rst = $mvs->ActualizarFacturas($facturas,$empresas['empresa_id'],UserGetUID(),$grupos['fac_grupo_id'],$grupos['sw_estado']);
			
			if($rst)
			{
				SessionDelVar("FacturasSeleccionadas");
				$objResponse->call("RecargarVista");
			}
			else
			{
				$html = "HA OCURRIDO UN ERROR DURANTE EL PROCESO<br>".$mvs->frmError['MensajeError'];
				$objResponse->assign("error","innerHTML",$html);
			}
		}
		return $objResponse;
	}
	
	function EliminarFacturasSelecciondas()
	{
		$objResponse = new xajaxResponse();
		$facturas = SessionGetVar("FacturasSeleccionadas");
		foreach($facturas as $key => $factura)
		{
			foreach($factura as $keyI => $prefijo)
			{
				$html = "							<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\" width=\"17\" height=\"17\">\n";
				$objResponse->assign($key.$keyI,"innerHTML",$html);
			}
		}

		SessionDelVar("FacturasSeleccionadas");
		return $objResponse;
	}

?>