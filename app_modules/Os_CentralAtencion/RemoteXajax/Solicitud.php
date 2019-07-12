<?php
	function BuscarCargos($cargo,$descripcion,$opcion,$off = 0)
	{
		IncludeClass('SolicitudManual','','app','Os_CentralAtencion');
		IncludeClass('ClaseHTML');
		
		$objResponse = new xajaxResponse();
		$slm = new SolicitudManual();
		
		$datos = SessionGetVar("CentralAtecion");
		
		$cargos = $slm->ObtenerCargos($cargo,$descripcion,$opcion,$datos['departamento'],$off);
		$html = "";
		if(!empty($cargos))
		{
			$Paginador = new ClaseHTML();
			$est = "modulo_list_claro";
			$action = "BuscarDatos('".$cargo."','".$descripcion."','".$opcion."'";
			$html .= "<br>\n";			
			$html .= $Paginador->ObtenerPaginadoXajax($slm->conteo,$slm->paginaActual,$action);
			
			$html .= "<table align=\"center\" class=\"modulo_table_list\" width=\"98%\">";
			$html .= "	<tr class=\"modulo_table_list_title\">\n";
			$html .= "  	<td width=\"15%\">TIPO APOYO</td>\n";
			$html .= "  	<td width=\"10%\">CARGO</td>\n";
			$html .= "  	<td width=\"%\">DESCRIPCION</td>\n";
			$html .= "  	<td width=\"2%\" ></td>\n";
			$html .= "	</tr>\n";
			foreach($cargos as $key => $rst)
			{
				($est == "modulo_list_claro")? $est = "modulo_list_oscuro": $est = "modulo_list_claro";
				
				$html .= "	<tr class=\"".$est."\">\n";
				$html .= "  	<td align=\"center\" class=\"normal_10AN\">".$rst['tipo']."</td>\n";
				$html .= "  	<td align=\"center\" class=\"normal_10AN\">".$rst['cargo']."</td>\n";
				$html .= "		<td class=\"label\">".$rst['descripcion']."</td>\n";
				$html .= "		<td>\n";
				$html .= "			<a href=\"javascript:AdicionarCargo('".$rst['cargo']."','".$rst['sw_cantidad']."','".$rst['apoyod_tipo_id']."')\" title=\"ADICIONAR CARGOS\">\n";
				$html .= "				<img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\">\n";
				$html .= "			</a>\n";
				$html .= "		</td>\n";
				$html .= "	</tr>\n";
			}
			$html .= "</table>\n";

			//$action = "BuscarDatos('".$cargo."','".$descripcion."','".$opcion."','".$slm->offset."')";
			$html .= $Paginador->ObtenerPaginadoXajax($slm->conteo,$slm->paginaActual,$action,true);
			$html .= "<br>\n";

		}			
		else
		{
			$html = "<label class=\"label_error\">LA BUSQUEDA NO ARROJO RESULTADOS</label>\n";
		}
		$html = utf8_encode( $html );
		$objResponse->assign("error_adicion","innerHTML","");
		$objResponse->assign("buscador","innerHTML",$html);
		$objResponse->assign("buscador","style.display","block");
		$objResponse->assign("equivalencia","style.display","none");
		$objResponse->call("LimpiarCampos");
		
		return $objResponse;
	}
	
	function AdicionarCargo($cargo,$plan,$inputs,$sw_cantidad,$apoyo_id)
	{
		IncludeClass('LiquidacionCargos');
		$lqc = new LiquidacionCargos();
		$div = "";
		$html = "";

		$cargos = $lqc->ValidarCargoMallaSolicitudManual($cargo,$plan);
		$objResponse = new xajaxResponse();
		if(is_array($cargos))
		{
			$adicion = SessionGetVar("CargosAdicionados");
			$adicion[$cargo] = $cargos;
			$adicion[$cargo][0]['sw_cantidad'] = $sw_cantidad;
			$adicion[$cargo][0]['apoyo_id'] = $apoyo_id;
			
			SessionSetVar("CargosAdicionados",$adicion);
			
			$html = TablaAdicionales($adicion);
			$objResponse->assign("boton_aceptar","style.display","block");
			$objResponse->assign("error_adicion","innerHTML","");
			$objResponse->assign("adicionados","innerHTML",$html);
		}
		else
		{
			$html = utf8_encode($lqc->ErrMsg());
			$objResponse->assign("error_adicion","innerHTML",$html);
		}
		
		$html = utf8_encode($html);
		
		
		return $objResponse;
	}
	
	function EliminarCargo($cargo,$trfs,$inputs)
	{
		$html = "";
		$cargos = SessionGetVar("CargosAdicionados");
		$ncrg = $cargos;

		foreach($cargos as  $key => $crgs)
		{
			foreach($crgs as $keyI => $crg)
			{
				if($key == $cargo)
				{
					unset($ncrg[$key][$keyI]);
					if(empty($ncrg[$key]))
						unset($ncrg[$key]);
				}
			}
		}
		//print_r($cargos);
		$objResponse = new xajaxResponse();
		if(!empty($ncrg))
		{
			$html = TablaAdicionales($ncrg);
			$html = utf8_encode($html);
		}
		else
		{
			$objResponse->assign("boton_aceptar","style.display","none");
		}
		SessionSetVar("CargosAdicionados",$ncrg);
		
		$objResponse->assign("adicionados","innerHTML",$html);
		$objResponse->assign("buscador","style.display","block");
		$objResponse->assign("equivalencia","style.display","none");
		return $objResponse;
	}
	
	function BuscarPaciente($ingreso,$cuenta,$tipo_documento,$documento,$nombre,$apellido,$offset)
	{
		IncludeClass('SolicitudManual','','app','Os_CentralAtencion');

		$objResponse = new xajaxResponse();
		$slm = new SolicitudManual();
		$datos = array();
		$datos['ingreso'] = $ingreso;
		$datos['nombres'] = $nombre ;
		$datos['apellidos'] = $apellido;
		$datos['paciente_id'] = $documento;
		$datos['numerodecuenta'] = $cuenta;
		$datos['tipo_id_paciente'] = $tipo_documento;
		
		$empresa = SessionGetVar("CentralAtecion");
		$paciente = $slm->ObtenerPacientes($datos,$empresa['empresa_id'],$offset);
		
		$html = "<label class=\"label_error\">SU BUSQUEDA NO RETORNO RESULTADOS</label>\n";
		if(!empty($paciente))
		{
			$action = "BuscarPacientesII('".$ingreso."','".$cuenta."','".$tipo_documento."','".$documento."','".$nombre."','".$apellido."'";
			$Paginador = new ClaseHTML();
			$html  = $Paginador->ObtenerPaginadoXajax($slm->conteo,$slm->paginaActual,$action,false,20);
			$html .= TablaPacientes($paciente);
			
			if($slm->conteo > ($slm->paginaActual*20) )
				$html .= "<br>".$Paginador->ObtenerPaginadoXajax($slm->conteo,$slm->paginaActual,$action,true,20);
		}
		
		$html = utf8_encode($html);
		$objResponse->assign("busquedahospitalaria","innerHTML",$html);
		return $objResponse;
	}
	
	function TablaPacientes($paciente)
	{
		$action = ModuloGetURL('app','Os_CentralAtencion','user','FormaIngresarCargos');

		$est = "modulo_list_claro";

		$html .= "<table align=\"center\" border=\"0\" width=\"100%\" class=\"modulo_table_list\">\n";
		$html .= "	<tr class=\"modulo_table_list_title\">\n";
		$html .= "		<td width=\"11%\">INGRESO</td>\n";
		$html .= "		<td width=\"11%\">Nº CUENTA</td>\n";
		$html .= "		<td width=\"45%\" colspan=\"2\">PACIENTE</td>\n";
		$html .= "		<td width=\"29%%\">PLAN</td>\n";
		$html .= "		<td width=\"%\" ></td>\n";
		$html .= "	</tr>\n";
		
		$est = "";
		$rqst = array();
		foreach($paciente as  $key => $datos)
		{
			($est == "modulo_list_claro")? $est = "modulo_list_oscuro": $est = "modulo_list_claro";
			
			$rqst['plan_id'] = $datos['plan_id'];
			$rqst['paciente_id'] = $datos['paciente_id'];
			$rqst['departamento'] = $datos['departamento_actual'];
			$rqst['tipo_id_paciente'] = $datos['tipo_id_paciente'];
			$rqst['afilia']['rango'] = $datos['tipo_afiliado_id'];
      $rqst['afilia']['Semanas'] = $datos['semanas_cotizadas'];
      $rqst['afilia']['tipoafiliado'] = $datos['tipo_afiliado_id'];
			
			$html .= "	<tr class=\"$est\">\n";
			$html .= "		<td align=\"center\">".$datos['ingreso']."</td>\n";
			$html .= "		<td align=\"center\">".$datos['numerodecuenta']."</td>\n";
			$html .= "		<td width=\"15%\">".$datos['tipo_id_paciente']." ".$datos['paciente_id']."</td>\n";
			$html .= "		<td>".$datos['nombre']." ".$datos['apellido']."</td>\n";				
			$html .= "		<td>".$datos['plan_descripcion']."</td>\n";				
			$html .= "		<td align=\"center\">\n";
			$html .= "			<a href=\"".$action.URLRequest($rqst)."\" title=\"CREAR SOLICITUD\">\n";
			$html .= "				<img src=\"".GetThemePath()."/images/pmodificar.png\" border=\"0\">\n";
			$html .= "			<a>\n";
			$html .= "		</td>\n";
			$html .= "	</tr>\n";
		}
		$html .= "</table>\n";
		
		return $html;
	}
	
	function TablaAdicionales($cargos)
	{
		$html .= "<table align=\"center\" border=\"0\" width=\"80%\" class=\"modulo_table_list\">\n";
		$html .= "	<tr class=\"modulo_table_list_title\">\n";
		$html .= "		<td colspan=\"4\">CARGOS SELECCIONADOS</td>\n";
		$html .= "	</tr>\n";
		$html .= "	<tr class=\"modulo_table_list_title\">\n";
		$html .= "		<td width=\"10%\">CUPS</td>\n";
		$html .= "		<td width=\"%\">DESCRIPCION</td>\n";
		$html .= "		<td width=\"5%\"></td>\n";
		$html .= "	</tr>\n";
	
		foreach($cargos as  $key => $adcc)
		{
			$j = 0;
			
			$crg = $adcc[0];
			
			$html .= "	<tr class=\"modulo_list_claro\">\n";
			$html .= "		<td >".$crg['cargo_base']."</td>\n";
			$html .= "		<td>".$crg['cups']."\n";
			$html .= "			<input type=\"hidden\" name=\"cargo_base\" value=\"".$crg['cargo_base']."\">\n";
			$html .= "			<input type=\"hidden\" name=\"cargo\" value=\"".$crg['cargo']."\">\n";
			$html .= "		</td>\n";
			$html .= "		<td  rowspan=\"".$clsp."\" align=\"center\">\n";
			$html .= "			<a href=\"javascript:Eliminar('".$crg['cargo_base']."','".$crg['tarifario_id']."')\" title=\"ELIMINAR CARGO\">\n";
			$html .= "				<img src=\"".GetThemePath()."/images/elimina.png\" border=\"0\">\n";
			$html .= "			</a>\n";
			$html .= "		</td>\n";				
			$html .= "	</tr>\n";
		}
		$html .= "</table>\n";
		
		return $html;
	}
?>