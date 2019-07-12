<?php

	function Notas_Enfermeria($datos,$titulo,$datos_user)
	{
		$registro_vacio=true;
		$salida="";
		$salida.= "		<div align='center'>\n";
		foreach($datos as $key=>$value){
			$vector[]=$key;
			if ($key!='fecha' && $key!='usuario_id' && $key!='ingreso' && !empty($datos[$key]) && $datos[$key]!=0){
				$registro_vacio=false;
			}
		}
		if (!$registro_vacio){
			$salida.= "		<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\" width='100%' class='table_notas_enfermeria'>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.=NE_CabeceraControl($titulo,$datos['fecha'],$datos_user);
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.= "					<table border='1' width='100%' class='table_list_notas_enfermeria'>\n";

			unset($datos['usuario_id']);
			unset($datos['ingreso']);
			unset($datos['usuario_id']);
			unset($datos['hc_ne_id']);
			for ($i=0;$i<sizeof($vector);$i++){
				if ( !is_null($datos[$vector[$i]]) && is_null(GetTipoVar($datos[$vector[$i]])) || (GetTipoVar($datos[$vector[$i]])!=0 && $vector[$i]!='fecha') ){
					//echo "<br><br>D->".$datos[$vector[$i]];
					$salida.= "						<tr>\n";
					$salida.= "							<td width='100%' colspan='2'>\n";
					$salida.= $datos[$vector[$i]];
					$salida.= "							</td>\n";
					$salida.= "						</tr>\n";
				}
			}
			$salida.= "					</table>\n";
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "		</table>\n";
			$salida.= "		</div>\n";
		}
	  return $salida;
	}



	function NE_SignosVitales($datos,$titulo,$datos_user)
	{
		$registro_vacio=true;
		$salida="";
		$salida.= "		<div align='center'>\n";
		foreach($datos as $key=>$value){
			$vector[]=$key;
			if ($key!='fecha' && $key!='usuario_id' && $key!='ingreso' && !empty($datos[$key]) && $datos[$key]!=0){
				$registro_vacio=false;
			}
		}
		if (!$registro_vacio){
			$salida.= "		<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\" width='100%' class='table_notas_enfermeria'>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.=NE_CabeceraControl($titulo,$datos['fecha'],$datos_user);
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.= "					<table border='1' width='100%' class='table_list_notas_enfermeria'>\n";
			$fc_min=$datos['min_fc'];
			$fc_max=$datos['max_fc'];
			$temp_min=$datos['min_temp'];
			$temp_max=$datos['max_temp'];
			$pvc_min=$datos['min_pvc'];
			$pvc_max=$datos['max_pvc'];

			unset($datos['usuario_id']);
			unset($datos['ingreso']);
			unset($datos['min_fc']);
			unset($datos['max_fc']);
			unset($datos['min_temp']);
			unset($datos['max_temp']);
			unset($datos['min_pvc']);
			unset($datos['max_pvc']);

			for ($i=0;$i<sizeof($vector);$i++){
				if ( !is_null($datos[$vector[$i]]) && is_null(GetTipoVar($datos[$vector[$i]])) || (GetTipoVar($datos[$vector[$i]])!=0 && $vector[$i]!='fecha') ){
					if ($vector[$i]=='fc' && ($datos[$vector[$i]]>=$fc_max || $datos[$vector[$i]]<=$fc_min)){
						$salida.= "						<tr class='alerta'>\n";
						$salida.= "							<td width='70%'>\n";
						$salida.= strtoupper(NE_NombreCampo_SV($vector[$i]));
					}
					elseif ($vector[$i]=='temp_piel' && ($datos[$vector[$i]]>=$temp_max || $datos[$vector[$i]]<=$temp_min)){
						$salida.= "						<tr class='alerta'>\n";
						$salida.= "							<td width='70%'>\n";
						$salida.= strtoupper(NE_NombreCampo_SV($vector[$i]));
					}
					elseif ($vector[$i]=='pvc' && ($datos[$vector[$i]]>=$pvc_max || $datos[$vector[$i]]<=$pvc_min)){
						$salida.= "						<tr class='alerta'>\n";
						$salida.= "							<td width='70%'>\n";
						$salida.= strtoupper(NE_NombreCampo_SV($vector[$i]));
					}
					else{
						$salida.= "						<tr>\n";
						$salida.= "							<td width='70%'>\n";
						$salida.= strtoupper(NE_NombreCampo_SV($vector[$i]));
					}
					$salida.= "							</td>\n";
					$salida.= "							<td width='30%'>\n";
					$salida.= $datos[$vector[$i]];
					$salida.= "							</td>\n";
					$salida.= "						</tr>\n";
				}
			}
			$salida.= "					</table>\n";
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "		</table>\n";
			$salida.= "		</div>\n";
		}
	  return $salida;
	}

	function NE_Glucometria($datos,$titulo,$datos_user)
	{
		$registro_vacio=true;
		$salida="";
		$salida.= "		<div align='center'>\n";
		foreach($datos as $key=>$value){
			$vector[]=$key;
			if ($key!='fecha' && $key!='usuario' && $key!='ingreso' && !empty($datos[$key]) && $datos[$key]!=0){
				$registro_vacio=false;
			}
		}
		if (!$registro_vacio){
			$salida.= "		<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\" width='100%' class='table_notas_enfermeria'>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.=NE_CabeceraControl($titulo,$datos['fecha'],$datos_user);
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.= "					<table border='1' width='100%' class='table_list_notas_enfermeria'>\n";
			$gluco_min=$datos['min_gluco'];
			$gluco_max=$datos['max_gluco'];

			unset($datos['usuario']);
			unset($datos['ingreso']);

			for ($i=0;$i<sizeof($vector);$i++){
				if ( !is_null($datos[$vector[$i]]) && is_null(GetTipoVar($datos[$vector[$i]])) || (GetTipoVar($datos[$vector[$i]])!=0 && $vector[$i]!='fecha') ){
					if ($vector[$i]=='glucometria' && ($datos[$vector[$i]]>=$gluco_max || $datos[$vector[$i]]<=$gluco_min)){
						$salida.= "						<tr class='alerta'>\n";
						$salida.= "							<td width='70%'>\n";
						$salida.= strtoupper(NE_NombreCampo_SV($vector[$i]));
					}
					else{
						$salida.= "						<tr>\n";
						$salida.= "							<td width='70%'>\n";
						$salida.= strtoupper(NE_NombreCampo_Glucometria($vector[$i]));
					}
					$salida.= "							</td>\n";
					$salida.= "							<td width='30%'>\n";
					$salida.= $datos[$vector[$i]];
					$salida.= "							</td>\n";
					$salida.= "						</tr>\n";
				}
			}
			$salida.= "					</table>\n";
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "		</table>\n";
			$salida.= "		</div>\n";
		}
	  return $salida;
	}


	function NE_HojaNeurologica($datos,$titulo,$datos_user)
	{
		$registro_vacio=true;
		$salida="";
		$salida.= "		<div align='center'>\n";
		foreach($datos as $key=>$value){
			$vector[]=$key;
			if ($key!='fecha' && $key!='usuario' && $key!='ingreso' && !empty($datos[$key]) && $datos[$key]!=0){
				$registro_vacio=false;
			}
		}
		if (!$registro_vacio){
			$salida.= "		<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\" width='100%' class='table_notas_enfermeria'>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.=NE_CabeceraControl($titulo,$datos['fecha'],$datos_user);
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.= "					<table border='1' width='100%' class='table_list_notas_enfermeria'>\n";
			$glasgow= ($datos['tipo_apertura_ocular_id']+$datos['tipo_respuesta_verbal_id']+$datos['tipo_respuesta_motora_id']);
			unset($datos['tipo_apertura_ocular_id']);
			unset($datos['tipo_respuesta_verbal_id']);
			unset($datos['tipo_respuesta_motora_id']);
			unset($datos['usuario_id']);
			unset($datos['ingreso']);

			for ($i=0;$i<sizeof($vector);$i++){
				if ( !is_null($datos[$vector[$i]]) && is_null(GetTipoVar($datos[$vector[$i]])) || (GetTipoVar($datos[$vector[$i]])!=0 && $vector[$i]!='fecha') ){
					$salida.= "						<tr>\n";
					$salida.= "							<td width='70%'>\n";
					$salida.= strtoupper(NE_NombreCampo_HojaNeurologica($vector[$i]));
					$salida.= "							</td>\n";
					$salida.= "							<td width='30%'>\n";
					$salida.= $datos[$vector[$i]];
					$salida.= "							</td>\n";
					$salida.= "						</tr>\n";
				}
			}
			$salida.= "						<tr>\n";
			if($glasgow < 8){
				$estilo = "GlasgowBajo";//rojo
			}
			elseif($glasgow >= 8 && $glasgow < 12){
				$estilo = "GlasgowIntermedio";//naranja
			}
			else{
				$estilo = "GlasgowAlto";//amarillo
			}
			$salida.= "							<td width='70%' class='$estilo'>ESCALA DE GLASGOW (Total 3 A 15)</td>\n";
			$salida.= "							<td width='30%' class='$estilo'>$glasgow</td>\n";
			$salida.= "						</tr>\n";
			$salida.= "					</table>\n";
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "		</table>\n";
			$salida.= "		</div>\n";
		}
	  return $salida;
	}


	function NE_GasesArteriales($datos,$titulo,$datos_user)
	{
		$registro_vacio=true;
		$salida="";
		$salida.= "		<div align='center'>\n";
		foreach($datos as $key=>$value){
			$vector[]=$key;
			if ($key!='fecha' && $key!='usuario' && $key!='ingreso' && !empty($datos[$key]) && $datos[$key]!=0){
				$registro_vacio=false;
			}
		}
		if (!$registro_vacio){
			$salida.= "		<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\" width='100%' class='table_notas_enfermeria'>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.=NE_CabeceraControl($titulo,$datos['fecha'],$datos_user);
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.= "					<table border='1' width='100%' class='table_list_notas_enfermeria'>\n";
			unset($datos['fio2']);
			unset($datos['usuario_id']);
			unset($datos['ingreso']);
			for ($i=0;$i<sizeof($vector);$i++){
				if ( !is_null($datos[$vector[$i]]) && is_null(GetTipoVar($datos[$vector[$i]])) || (GetTipoVar($datos[$vector[$i]])!=0 && $vector[$i]!='fecha') ){
					$salida.= "						<tr>\n";
					$salida.= "							<td width='70%'>\n";
					$salida.= strtoupper(NE_NombreCampo_Gases($vector[$i]));
					$salida.= "							</td>\n";
					$salida.= "							<td width='30%'>\n";
					$salida.= $datos[$vector[$i]];
					$salida.= "							</td>\n";
					$salida.= "						</tr>\n";
				}
			}
			$salida.= "					</table>\n";
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "		</table>\n";
			$salida.= "		</div>\n";
		}
	  return $salida;
	}


	function NE_GasesVenosos($datos,$titulo,$datos_user)
	{
		$registro_vacio=true;
		$salida="";
		$salida.= "		<div align='center'>\n";
		foreach($datos as $key=>$value){
			$vector[]=$key;
			if ($key!='fecha' && $key!='usuario' && $key!='ingreso' && !empty($datos[$key]) && $datos[$key]!=0){
				$registro_vacio=false;
			}
		}
		if (!$registro_vacio){
			$salida.= "		<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\" width='100%' class='table_notas_enfermeria'>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.=NE_CabeceraControl($titulo,$datos['fecha'],$datos_user);
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.= "					<table border='1' width='100%' class='table_list_notas_enfermeria'>\n";
			unset($datos['fio2']);
			unset($datos['usuario_id']);
			unset($datos['ingreso']);
			for ($i=0;$i<sizeof($vector);$i++){
				if ( !is_null($datos[$vector[$i]]) && is_null(GetTipoVar($datos[$vector[$i]])) || (GetTipoVar($datos[$vector[$i]])!=0 && $vector[$i]!='fecha') ){
					$salida.= "						<tr>\n";
					$salida.= "							<td width='70%'>\n";
					$salida.= strtoupper(NE_NombreCampo_Gases($vector[$i]));
					$salida.= "							</td>\n";
					$salida.= "							<td width='30%'>\n";
					$salida.= $datos[$vector[$i]];
					$salida.= "							</td>\n";
					$salida.= "						</tr>\n";
				}
			}
			$salida.= "					</table>\n";
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "		</table>\n";
			$salida.= "		</div>\n";
		}
	  return $salida;
	}

	function NE_AsistenciaVentilatoria($datos,$titulo,$datos_user)
	{
		$registro_vacio=true;
		$salida="";
		$salida.= "		<div align='center'>\n";
		foreach($datos as $key=>$value){
			$vector[]=$key;
			if ($key!='fecha' && $key!='usuario' && $key!='ingreso' && !empty($datos[$key]) && $datos[$key]!=0){
				$registro_vacio=false;
			}
		}
		if (!$registro_vacio){
			$salida.= "		<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\" width='100%' class='table_notas_enfermeria'>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.=NE_CabeceraControl($titulo,$datos['fecha'],$datos_user);
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.= "					<table border='1' width='100%' class='table_list_notas_enfermeria'>\n";
			$av_min=$datos['min_av'];
			$av_max=$datos['max_av'];

			unset($datos['f102_id']);
			unset($datos['modo_id']);
			unset($datos['usuario_id']);
			unset($datos['ingreso']);
			unset($datos['min_av']);
			unset($datos['max_av']);

			for ($i=0;$i<sizeof($vector);$i++){
				if ( !is_null($datos[$vector[$i]]) && is_null(GetTipoVar($datos[$vector[$i]])) || (GetTipoVar($datos[$vector[$i]])!=0 && $vector[$i]!='fecha') ){
					if ($vector[$i]=='fr_respiratoria' && ($datos[$vector[$i]]>=$av_max || $datos[$vector[$i]]<=$av_min)){
						$salida.= "						<tr class='alerta'>\n";
						$salida.= "							<td width='70%'>\n";
						$salida.= strtoupper(NE_NombreCampo_AsistenciaVentilatoria($vector[$i]));
					}
					else{
						$salida.= "						<tr>\n";
						$salida.= "							<td width='70%'>\n";
						$salida.= strtoupper(NE_NombreCampo_AsistenciaVentilatoria($vector[$i]));
					}
					$salida.= "							</td>\n";
					$salida.= "							<td width='30%'>\n";
					$salida.= $datos[$vector[$i]];
					$salida.= "							</td>\n";
					$salida.= "						</tr>\n";
				}
			}
			$salida.= "					</table>\n";
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "		</table>\n";
			$salida.= "		</div>\n";
		}
	  return $salida;
	}


	function NE_NotasMedicamentos($datos,$titulo,$datos_user)
	{
		$registro_vacio=true;
		$salida="";
		$salida.= "		<div align='center'>\n";
		foreach($datos as $key=>$value){
			$vector[]=$key;
			if ($key!='fecha' && $key!='usuario' && $key!='ingreso' && !empty($datos[$key]) && $datos[$key]!=0){
				$registro_vacio=false;
			}
		}
		if (!$registro_vacio){
			$salida.= "		<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\" width='100%' class='table_notas_enfermeria'>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.=NE_CabeceraControl($titulo,$datos['fecha'],$datos_user);
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.= "					<table border='1' width='100%' class='table_list_notas_enfermeria'>\n";
			unset($datos['nota_id']);
			unset($datos['evolucion_id']);
			unset($datos['evolucion_nota_id']);
			unset($datos['tipo_nota']);
			unset($datos['usuario_id']);
			unset($datos['ingreso']);

			for ($i=0;$i<sizeof($vector);$i++){
				if ( !is_null($datos[$vector[$i]]) && is_null(GetTipoVar($datos[$vector[$i]])) || (GetTipoVar($datos[$vector[$i]])!=0 && $vector[$i]!='fecha') ){
					$salida.= "						<tr>\n";
					$salida.= "							<td width='70%'>\n";
					$salida.= strtoupper(NE_NombreCampo_NotasMedicamentos($vector[$i]));
					$salida.= "							</td>\n";
					$salida.= "							<td width='30%'>\n";
					$salida.= $datos[$vector[$i]];
					$salida.= "							</td>\n";
					$salida.= "						</tr>\n";
				}
			}
			$salida.= "					</table>\n";
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "		</table>\n";
			$salida.= "		</div>\n";
		}
	  return $salida;
	}


	function NE_NotasMezclas($datos,$titulo,$datos_user)
	{
		$registro_vacio=true;
		$salida="";
		$salida.= "		<div align='center'>\n";
		foreach($datos as $key=>$value){
			$vector[]=$key;
			if ($key!='fecha' && $key!='usuario' && $key!='ingreso' && !empty($datos[$key]) && $datos[$key]!=0){
				$registro_vacio=false;
			}
		}
		if (!$registro_vacio){
			$salida.= "		<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\" width='100%' class='table_notas_enfermeria'>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.=NE_CabeceraControl($titulo,$datos['fecha'],$datos_user);
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.= "					<table border='1' width='100%' class='table_list_notas_enfermeria'>\n";
			unset($datos['dat_medicamentos']);
			unset($datos['mezcla_recetada_id']);
			unset($datos['nota_id']);
			unset($datos['evolucion_id']);
			unset($datos['evolucion_nota_id']);
			unset($datos['tipo_nota']);
			unset($datos['usuario_id']);
			unset($datos['ingreso']);

			for ($i=0;$i<sizeof($vector);$i++){
				if ( !is_null($datos[$vector[$i]]) && is_null(GetTipoVar($datos[$vector[$i]])) || (GetTipoVar($datos[$vector[$i]])!=0 && $vector[$i]!='fecha') ){
					$salida.= "						<tr>\n";
					$salida.= "							<td width='70%'>\n";
					$salida.= strtoupper(NE_NombreCampo_NotasMezclas($vector[$i]));
					$salida.= "							</td>\n";
					$salida.= "							<td width='30%'>\n";
					$salida.= $datos[$vector[$i]];
					$salida.= "							</td>\n";
					$salida.= "						</tr>\n";
				}
			}
			$salida.= "					</table>\n";
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "		</table>\n";
			$salida.= "		</div>\n";
		}
	  return $salida;
	}


	function NE_Medicamentos_Suspendidos($datos,$titulo,$datos_user)
	{
		$registro_vacio=true;
		$salida="";
		$salida.= "		<div align='center'>\n";
		foreach($datos as $key=>$value){
			$vector[]=$key;
			if ($key!='fecha' && $key!='usuario' && $key!='ingreso' && !empty($datos[$key]) && $datos[$key]!=0){
				$registro_vacio=false;
			}
		}
		if (!$registro_vacio){
			$salida.= "		<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\" width='100%' class='table_notas_enfermeria'>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.=NE_CabeceraControl($titulo,$datos['fecha'],$datos_user);
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.= "					<table border='1' width='100%' class='table_list_notas_enfermeria'>\n";

			unset($datos['sw_estado']);
			unset($datos['evolucion_id']);
			unset($datos['sw_pos']);
			unset($datos['via_administracion_id']);
			unset($datos['horario']);
			unset($datos['sw_rango']);
			unset($datos['justificacion_no_pos_id']);
			unset($datos['empresa_id']);
			unset($datos['centro_utilidad']);
			unset($datos['bodega']);
			unset($datos['unidad_dosis']);
			unset($datos['usuario_id']);

			for ($i=0;$i<sizeof($vector);$i++){
				if ( !empty($datos[$vector[$i]]) && !is_null($datos[$vector[$i]]) && is_null(GetTipoVar($datos[$vector[$i]])) || (GetTipoVar($datos[$vector[$i]])!=0 && $vector[$i]!='fecha') ){
					if ($vector[$i]=='nota_suspension'){
						$salida.= "						<tr class='alerta'>\n";
						$salida.= "							<td width='70%'>\n";
						$salida.= strtoupper(NE_NombreCampo_Medicamentos_Suspendidos($vector[$i]));
					}
					else{
						$salida.= "						<tr>\n";
						$salida.= "							<td width='70%'>\n";
						$salida.= strtoupper(NE_NombreCampo_Medicamentos_Suspendidos($vector[$i]));
					}
					$salida.= "							</td>\n";
					$salida.= "							<td width='30%'>\n";
					$salida.= $datos[$vector[$i]];
					$salida.= "							</td>\n";
					$salida.= "						</tr>\n";
				}
			}
			$salida.= "					</table>\n";
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "		</table>\n";
			$salida.= "		</div>\n";
		}
	  return $salida;
	}


	function NE_Mezclas_Suspendidas($datos,$titulo,$datos_user)
	{
		$registro_vacio=true;
		$salida="";
		$salida.= "		<div align='center'>\n";
		foreach($datos as $key=>$value){
			$vector[]=$key;
			if ($key!='fecha' && $key!='usuario' && $key!='ingreso' && !empty($datos[$key]) && $datos[$key]!=0){
				$registro_vacio=false;
			}
		}
		if (!$registro_vacio){
			$salida.= "		<table cellpadding=\"2\" cellspacing=\"2\" border=\"0\" width='100%' class='table_notas_enfermeria'>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.=NE_CabeceraControl($titulo,$datos['fecha'],$datos_user);
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "			<tr>\n";
			$salida.= "				<td>\n";
			$salida.= "					<table border='1' width='100%' class='table_list_notas_enfermeria'>\n";

			unset($datos['dat_medicamentos']);
			unset($datos['sw_estado']);
			unset($datos['mezcla_recetada_id']);
			unset($datos['evolucion_id']);
			unset($datos['usuario_id']);

			for ($i=0;$i<sizeof($vector);$i++){
				if ( !is_null($datos[$vector[$i]]) && is_null(GetTipoVar($datos[$vector[$i]])) || (GetTipoVar($datos[$vector[$i]])!=0 && $vector[$i]!='fecha') ){
					if ($vector[$i]=='nota_suspension'){
						$salida.= "						<tr class='alerta'>\n";
						$salida.= "							<td width='70%'>\n";
						$salida.= strtoupper(NE_NombreCampo_Mezclas_Suspendidas($vector[$i]));
					}
					else{
						$salida.= "						<tr>\n";
						$salida.= "							<td width='70%'>\n";
						$salida.= strtoupper(NE_NombreCampo_Mezclas_Suspendidas($vector[$i]));
					}
					$salida.= "							</td>\n";
					$salida.= "							<td width='30%'>\n";
					$salida.= $datos[$vector[$i]];
					$salida.= "							</td>\n";
					$salida.= "						</tr>\n";
				}
			}
			$salida.= "					</table>\n";
			$salida.= "				</td>\n";
			$salida.= "			</tr>\n";
			$salida.= "		</table>\n";
			$salida.= "		</div>\n";
		}
	  return $salida;
	}


	function NE_NombreCampo_SV($campo)
	{
		if ($campo=='fc') return "frecuencia cardiaca";
		if ($campo=='pvc') return "presion venosa central";
		if ($campo=='temp_piel') return "temperatura";
		if ($campo=='presion_intracraneana') return "presion intracraneana";
		if ($campo=='peso') return "peso";
		return $campo;
	}

	function NE_NombreCampo_Glucometria($campo)
	{
		if ($campo=='tipo_insulina_id') return "tipo insulina";
		if ($campo=='usuario') return "usuario_id";
		return $campo;
	}

	function NE_NombreCampo_HojaNeurologica($campo)
	{
		if ($campo=='pupila_talla_d') return "talla pupila derecha";
		if ($campo=='pupila_talla_i') return "talla pupila izquierda";
		if ($campo=='pupila_reaccion_d') return "reaccion pupila derecha";
		if ($campo=='pupila_reaccion_i') return "reaccion pupila izquierda";
		if ($campo=='tipo_nivel_consciencia_id') return "Nivel consciencia";
		if ($campo=='fuerza_brazo_d') return "fuerza brazo derecho";
		if ($campo=='fuerza_brazo_i') return "fuerza brazo izquierdo";
		if ($campo=='fuerza_pierna_d') return "fuerza pierna derecha";
		if ($campo=='fuerza_pierna_i') return "fuerza pierna izquierda";
		if ($campo=='tipo_apertura_ocular_ids') return "apertura ocular";
		if ($campo=='tipo_respuesta_verbal_ids') return "respuesta verbal";
		if ($campo=='tipo_respuesta_motora_ids') return "respuesta motora";
		return $campo;
	}

	function NE_NombreCampo_Gases($campo)
	{
		if ($campo=='fio2_art') return "fio2";
		return $campo;
	}

	function NE_NombreCampo_AsistenciaVentilatoria($campo)
	{
		if ($campo=='fr_respiratoria') return "frecuencia respiratoria";
		if ($campo=='fr_ventilatoria') return "frecuencia ventilatoria";
		if ($campo=='i_e') return "i:e";
		if ($campo=='t_via_a') return "to vía a";
		if ($campo=='f102_ids') return "fio2";
		if ($campo=='modo_ids') return "modo";
		return $campo;
	}

	function NE_NombreCampo_NotasMedicamentos($campo)
	{
		if ($campo=='medicamento_id') return "código medicamento";
		if ($campo=='dat_medicamento') return "nombre medicamento";
		return $campo;
	}

	function NE_NombreCampo_NotasMezclas($campo)
	{
		if (substr_count($campo,'dat_medicamento')) return "código medicamento";
		return $campo;
	}

	function NE_NombreCampo_Medicamentos_Suspendidos($campo)
	{
		if ($campo=='nota_suspension') return "nota de suspensión";
		if ($campo=='dat_medicamento') return "nombre medicamento";
		if ($campo=='duracion_id') return "tomar durante (el,la)";
		return $campo;
	}

	function NE_NombreCampo_Mezclas_Suspendidas($campo)
	{
		if ($campo=='via_administracion_id') return "via de administracion";
		if ($campo=='unidad_via') return "unidad via administracion";
		if ($campo=='unidad_calculo') return "unidad de suministro";
		if ($campo=='nota_suspension') return "nota de suspensión";
		if (substr_count($campo,'dat_medicamento')) return "nombre medicamento";
		return $campo;
	}

	function GetTipoVar($var)
	{
		$campo=sscanf($var,"%f");
			return $campo[0];
	}


?>
