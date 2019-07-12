<?php
	function ObtenerObservacion($key)
	{
		$observaciones = array();
		$objResponse = new xajaxResponse();
		$observaciones = SessionGetvar("ObservacionesCargos");
		//echo $key;
		$obs = utf8_encode($observaciones[$key]);
		$objResponse->assign("observacion_i","value",$obs);
		
		return $objResponse;
	}
	
	function IngresarObservacion($key,$observacion)
	{
		$observaciones = array();
		
		$objResponse = new xajaxResponse();
		$observaciones = SessionGetvar("ObservacionesCargos");
		$observaciones[$key] = $observacion;

		SessionSetvar("ObservacionesCargos",$observaciones);
		$objResponse->assign("observacion_i","value","");
		
		return $objResponse;
	}
	
	function ActualizarDetalleGlosa($motivo,$observacion,$valor,$auditor,$detalle,$glosa_id,$tipo,$concepto_especifico)
	{	
		$datos = array();
		$dat = explode("||//",$concepto_especifico);
		
		$datos['motivo_id'] = $motivo;
		$datos['observacion'] = $observacion;
		$datos['valor_glosa'] = $valor;
		$datos['auditor_id'] = $auditor;
		$datos['detalle_id'] = $detalle;
		$datos['glosa_id'] = $glosa_id;
		$datos['tipo'] = $tipo;
		$datos['concepto_general'] = $dat[0];
		$datos['concepto_especifico'] = $dat[1];
		
		IncludeClass('GlosaDetalle','','app','Glosas');
		$gld = new GlosaDetalle();
		
		$rst = $gld->ActualizarDetalle($datos);
		$html = "LA GLOSA SOBRE EL CARGO O INSUMO DE LA CUENTA SE HA MODIFICADO CORRECTAMENTE";
		
		if(!$rst)	$html = $gld->frmError['MensajeError'];
		
		$html = utf8_encode($html);
		
		$objResponse = new xajaxResponse();
		$objResponse->assign("mensaje","innerHTML",$html);
		
		return $objResponse;
	}
	
	function AnularDetalleGlosa($observacion,$detalle_cuenta,$detalle,$glosa_id,$tipo)
	{	
		$datos = array();
		
		$datos['observacion'] = utf8_decode($observacion);
		$datos['detalle_cuenta'] = $detalle_cuenta;
		$datos['detalle_id'] = $detalle;
		$datos['glosa_id'] = $glosa_id;
		$datos['tipo'] = $tipo;
		
		IncludeClass('GlosaDetalle','','app','Glosas');
		$gld = new GlosaDetalle();
		
		$rst = $gld->AnularGlosaCargoInsumo($datos);
		$html  = "	<form name=\"anulacion\" action=\"javascript:document.cancelar.submit()\" method=\"post\">\n";
		$html .= "			<center><br><br>\n";
		$html .= "				<label class=\"normal_10AN\">\n";
		$html .= "					LA ANULACION SOBRE LA GLOSA DEL CARGO O EL INSUMO DE LA CUENTA SE HA REGISTRADO CORRECTAMENTE";
		$html .= "				</label>\n";
		$html .= "				<br><br><input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
		$html .= "			</center><br>\n";
		$html .= "		</form>\n";
		
		$objResponse = new xajaxResponse();
		if(!$rst)	
		{
			$html = $gld->frmError['MensajeError'];
			$html = utf8_encode($html);
			$objResponse->assign("mensaje","innerHTML",$html);
		}
		else
		{
			$html = utf8_encode($html);
			$objResponse->assign("ContenidoI","innerHTML",$html);
			$objResponse->assign("cerrarI","innerHTML","");
		}
		
		return $objResponse;
	}
	
	function AnularGlosaCuenta($observacion,$detalle_cuenta,$glosa_id)
	{	
		$datos = array();
		
		$datos['observacion'] = utf8_decode($observacion);
		$datos['detalle_cuenta'] = $detalle_cuenta;
		$datos['glosa_id'] = $glosa_id;
		$datos['tipo'] = $tipo;
		
		IncludeClass('GlosaDetalle','','app','Glosas');
		$gld = new GlosaDetalle();
		
		$rst = $gld->AnularGlosaCuenta($datos);
		$html  = "	<form name=\"anulacion\" action=\"javascript:document.recargar.submit()\" method=\"post\">\n";
		$html .= "			<center><br><br>\n";
		$html .= "				<label class=\"normal_10AN\">\n";
		$html .= "					LA ANULACION SOBRE LA GLOSA DE LA CUENTA SE HA REGISTRADO CORRECTAMENTE";
		$html .= "				</label>\n";
		$html .= "				<br><br><input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
		$html .= "			</center><br>\n";
		$html .= "		</form>\n";
		
		$objResponse = new xajaxResponse();
		if(!$rst)	
		{
			$html = $gld->frmError['MensajeError'];
			$html = utf8_encode($html);
			$objResponse->assign("error","innerHTML",$html);
		}
		else
		{
			$html = utf8_encode($html);
			$objResponse->assign("ContenidoI","innerHTML",$html);
			$objResponse->assign("cerrarI","innerHTML","");
		}
		
		return $objResponse;
	}
	
	function AnularGlosa($observacion,$glosa_id)
	{	
		$datos = array();
		
		$datos['observacion'] = utf8_decode($observacion);
		$datos['glosa_id'] = $glosa_id;
				
		IncludeClass('GlosaDetalle','','app','Glosas');
		$gld = new GlosaDetalle();
		
		$rst = $gld->AnularGlosa($datos);
		$html  = "	<form name=\"anulacion\" action=\"javascript:document.volver.submit()\" method=\"post\">\n";
		$html .= "			<center><br><br>\n";
		$html .= "				<label class=\"normal_10AN\">\n";
		$html .= "					LA ANULACION DE LA GLOSA Nº ".$glosa_id." SE HA REGISTRADO CORRECTAMENTE";
		$html .= "				</label>\n";
		$html .= "				<br><br><input type=\"submit\" class=\"input-submit\" value=\"Aceptar\">\n";
		$html .= "			</center><br>\n";
		$html .= "		</form>\n";
		
		$objResponse = new xajaxResponse();
		if(!$rst)	
		{
			$html = $gld->frmError['MensajeError'];
			$html = utf8_encode($html);
			$objResponse->assign("error","innerHTML",$html);
		}
		else
		{
			$html = utf8_encode($html);
			$objResponse->assign("ContenidoI","innerHTML",$html);
			$objResponse->assign("cerrarI","innerHTML","");
		}
		
		return $objResponse;
	}
	
	function ModificarValores($detalle_cuenta,$valor,$campo)
	{
		$observaciones = array();
		$objResponse = new xajaxResponse();
		IncludeClass('Glosas','','app','Glosas');
		$gl = new Glosas();
		$rst = $gl->ActualizarValor($detalle_cuenta,$valor,$campo);
		
		return $objResponse;
	}
	
	function AsignarConceptos($tipo,$transaccion,$concepto_general,$concepto_especifico,$codigo_concepto_general)
	{
		$objResponse = new xajaxResponse();
		$html = "<label class=\"label_mark\">".$concepto_general." / ".$concepto_especifico."</label>";
		if($tipo == 'C')
		{
		  $obj = "cargos_";
		  $objResponse->assign("conceptocargos_".$transaccion,"style.display","none");
		}
		elseif($tipo == 'I')
		{
		  $obj = "insumos_";
		  $objResponse->assign("conceptoinsumos_".$transaccion,"style.display","none");
		}
		$objResponse->assign($obj.$transaccion,"innerHTML",$html);
			
		return $objResponse;
	}
	
/*	function LimparConceptos($transaccion,$concepto_general,$concepto_especifico,$codigo_concepto_general)
	{
		$objResponse = new xajaxResponse();
		$html = "";
		$objResponse->assign("cargos_".$transaccion,"innerHTML",$html);
			
		return $objResponse;
	}*/
  
  function ObtenerConcepGeneralesEspecificos($cod_con_gen, $transaccion, $cargo_cups, $key)
  {
    $objResponse = new xajaxResponse();
    
    if($cod_con_gen != 'V')
    {
      IncludeClass('Glosas', '', 'app', 'Glosas');
      $gl = new Glosas();
      $con_gen_esp = $gl->ObtenerConcepGeneralesEspecificos($cod_con_gen);
      
      $html  = "				<table align=\"center\" cellpading=\"0\"  width=\"60%\" border=\"0\" class=\"modulo_table_list\">\n";
      $html .= "					<tr class=\"modulo_table_list_title\">\n";
      $html .= "						<td style=\"text-align:left;text-indent:8pt\"><b>SEL</b>\n";
      $html .= "						</td>\n";
      $html .= "						<td style=\"text-align:left;text-indent:8pt\"><b>ID</b>\n";
      $html .= "						</td>\n";
      $html .= "						<td style=\"text-align:left;text-indent:8pt\"><b>DESCRIP.</b>\n";
      $html .= "						</td>\n";
      $html .= "					</tr>\n";
      
      foreach($con_gen_esp as $indice => $valor)
      {
        $html .= "				<tr class=\"modulo_table_list\">\n";
        if(empty($transaccion))
        {
          $html .= "					<td style=\"text-align:left;text-indent:8pt\"><input type=\"radio\" name=\"concepto_especifico\" value=\"".$valor['codigo_concepto_general']."||//".$valor['codigo_concepto_especifico']."\">\n";
          $html .= "					</td>\n";
        }else if(empty($key)){
          $html .= "					<td style=\"text-align:left;text-indent:8pt\"><input type=\"radio\" name=\"cargos[".$transaccion."][".$cargo_cups."][conceptos]\" value=\"".$valor['codigo_concepto_general']."||//".$valor['codigo_concepto_especifico']."\" onclick=\"xajax_AsignarConceptos('C','".$transaccion."','".$valor['descripcion_concepto_general']."','".$valor['descripcion_concepto_especifico']."','".$valor['codigo_concepto_general']."');\">\n";
          $html .= "					</td>\n";
        }else{
          $html .= "					<td style=\"text-align:left;text-indent:8pt\"><input type=\"radio\" name=\"insumos[".$key."][conceptos]\" value=\"".$valor['codigo_concepto_general']."||//".$valor['codigo_concepto_especifico']."\" onclick=\"xajax_AsignarConceptos('I','".$transaccion."','".$valor['descripcion_concepto_general']."','".$valor['descripcion_concepto_especifico']."','".$valor['codigo_concepto_general']."');\"\n";
          $html .= "					</td>\n";
        }
        $html .= "					<td style=\"text-align:left;text-indent:8pt\"><b>".$valor['codigo_concepto_especifico']."</b>\n";
        $html .= "					</td>\n";
        $html .= "					<td style=\"text-align:left;text-indent:8pt\"><b>".$valor['descripcion_concepto_especifico']."</b>\n";
        $html .= "					</td>\n";
        $html .= "				</tr>\n";
      }
      $html .= "				</table><br>\n";
      
      //$objResponse->alert("HTML ".$html);
      if(empty($transaccion))
      {
        $objResponse->assign("div_con_gen", "innerHTML", $html);
        $objResponse->assign("div_con_gen", "style.display", "block");
      }
      else if(empty($key))
      {
        $objResponse->assign("conceptocargos_".$transaccion."", "innerHTML", $html);
        $objResponse->assign("conceptocargos_".$transaccion."", "style.display", "block");
      }
      else 
      {
        $objResponse->assign("conceptoinsumos_".$transaccion."", "innerHTML", $html);       
        $objResponse->assign("conceptoinsumos_".$transaccion."", "style.display", "block");       
      }  
    }
    return $objResponse;
  }
?>