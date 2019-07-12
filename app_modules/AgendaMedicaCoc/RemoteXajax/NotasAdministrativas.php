<?php
	
	function reqNotasAdministrativas($fechaCita,$horaCita,$tipoProfesionalId,$ProfesionalId,$TipoId,$PacienteId,$existeNota_id)
	{
		$objResponse = new xajaxResponse();
		$datos = ConsultarNotaAdministrativa($fechaCita,$horaCita);
		if(!is_array($datos))
		{$datos = 'NoExiste';}
		$ventana = CrearVentanaNotaAdministrativa($fechaCita,$horaCita,$tipoProfesionalId,$ProfesionalId,$TipoId,$PacienteId,$datos);
		$objResponse->assign("d2Contents","innerHTML",$ventana);
		$objResponse->call('Iniciar');
		$objResponse->call('MostrarVentana');
		return $objResponse;
	}
		
	function CrearVentanaNotaAdministrativa($fechaCita,$horaCita,$tipoProfesionalId,$ProfesionalId,$TipoId,$PacienteId,$datos)
	{
		$ventana = "  <form name=\"formaNota\" action=\"$action\" method=\"post\">";            
		$ventana .= "  <table align=\"center\">";
		if($mensaje){
			$ventana .= "    <tr align=\"center\"><td align=\"center\" class=\"label_error\" colspan=\"6\">$mensaje</td></tr>";
		}
		if(is_array($datos))
		{
			$observaciones = $datos[observaciones];
			$existeNota_id = $datos[notas_administrativas_consulta_externa_id];
		}
		else
		{$existeNota_id = 'NoExiste';}
		$ventana .= "    <tr align=\"center\">";
		$ventana .= "    <td align=\"center\" class=\"Menu\" colspan=\"6\"><b>OBSERVACIONES NOTA ADMINISTRATIVA</b></td>";
		$ventana .= "    </tr>";
		$ventana .= "    <tr class=\"modulo_table_title\" align=\"center\"><td align=\"center\" colspan=\"6\">FECHA CITA: $fechaCita $horaCita</td></tr>\n";    
		$ventana .= "    <tr class=\"modulo_list_claro\">\n";
		$ventana .= "        <td><textarea name=\"Observaciones\" cols=\"45\" rows=\"3\" class=\"textarea\">$observaciones</textarea></td>";
		$ventana .= "    <tr><td></td></tr>\n";
		$ventana .= "    <tr><td colspan=\"6\" align=\"center\">\n";
		$ventana .= "    <input type=\"button\" class=\"input-submit\" name=\"insertar\" value=\"ACEPTAR\" onclick=\"xajax_InsertarNota(document.formaNota.Observaciones.value,'$fechaCita', '$horaCita','$tipoProfesionalId','$ProfesionalId','$TipoId','$PacienteId','$existeNota_id')\"></td></tr>\n";    
		$ventana .= "  </table><BR>"; 
		//$ventana .= MostrarFechasVencimiento($codigo_producto,$valor,$cantidad);    
		$ventana .= "  </form>";
		return $ventana;
	}
	
	function InsertarNota($Observaciones,$fechaCita,$horaCita,$tipoProfesionalId,$ProfesionalId,$TipoId,$PacienteId,$existeNota_id)
	{
		$objResponse = new xajaxResponse();
		if($existeNota_id <> 'NoExiste')
		{
			$sql = "UPDATE notas_administrativas_consulta_externa
							SET observaciones = '$Observaciones'
							WHERE notas_administrativas_consulta_externa_id = $existeNota_id;";
		}
		else
		{
			$sql = "INSERT INTO notas_administrativas_consulta_externa
							(
								fecha_cita,
								hora_cita, 
								tipo_id_tercero,
								tercero_id,
								tipo_id_paciente ,
								paciente_id,
								fecha_registro,
								observaciones
							)
							VALUES
							(
								'$fechaCita',
								'$horaCita',
								'$tipoProfesionalId',
								'$ProfesionalId',
								'$TipoId',
								'$PacienteId',
								now(),
								'$Observaciones'
							);";
		}
			if(!$rst = ConexionBaseDatos($sql)) return false;
		$nota = "<img src=\"".GetThemePath()."/images/ok.png\" title=\"$Observaciones\">";
		$objResponse->assign("nota$horaCita","innerHTML",$nota);
		$objResponse->call("Cerrar");
		
/*		$evento = "onClick=\"xajax_reqNotasAdministrativas('$fechaCita','$horaCita','$tipoProfesionalId','$ProfesionalId','$TipoId','$PacienteId','71');\"";
		$html = "<td align=\"center\" $evento><img src=\"".GetThemePath()."/images/ok.png\" title=\"$Observaciones\"></td>";
		$objResponse->assign("$horaCita","innerHTML",$html);
		$objResponse->call("Cerrar");*/
		return $objResponse;
	}
	
	/*
	**
	*/
	function ConsultarNotaAdministrativa($fechaCita,$horaCita)
	{
		$sql = "SELECT *
						FROM notas_administrativas_consulta_externa
						--WHERE notas_administrativas_consulta_externa_id = $existeNota_id
						WHERE fecha_cita::date = '$fechaCita'::date
						AND hora_cita = '$horaCita'
						;";
		if(!$rst = ConexionBaseDatos($sql)) return false;
		
		if(!$rst->EOF)
		{
			$datos = $rst->GetRowAssoc($ToUpper = false);;
		}
		return $datos;
	}
		/*********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		*
		* @access public  
		* @param  string  $sql  sentencia sql a ejecutar 
		* @return rst 
		************************************************************************************/
	function ConexionBaseDatos($sql)
	{
		list($dbconn)=GetDBConn();
		//$dbconn->debug=true;
		$rst = $dbconn->Execute($sql);
			
		if ($dbconn->ErrorNo() != 0)
		{
			$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
			echo "<b class=\"label\">".$this->frmError['MensajeError']."</b>";
			return false;
		}
		return $rst;
	}    
?>