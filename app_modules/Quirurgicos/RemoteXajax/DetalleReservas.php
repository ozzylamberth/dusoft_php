<?php
	
     function Busqueda($quirofano, $horario, $DiaEspe, $SelCapa)
     {
          $objResponse = new xajaxResponse();
          $html = VerDetalleSobreReserva($horario,$quirofano,$DiaEspe);
          if($html)
          {
          	// Selector de Capas
               if($SelCapa == '1')
               {
                    $objResponse->assign("ContenidoCapaInfo","style.display","");
				$objResponse->assign("ContenidoCapaInfo","innerHTML",$html);

               }elseif($SelCapa == '2')
          	{
                    $objResponse->assign("ContenidoCapaProg","style.display","");
				$objResponse->assign("ContenidoCapaProg","innerHTML",$html);
               }
          }
          return $objResponse;
     }
	
     
     /**
     * Funcion que muestra las reservas que existen sobre un rango de tiempo
     * @return boolean
     */
	function VerDetalleSobreReserva($horario,$quirofano,$DiaEspe)
     {
	     list($dbconn) = GetDBconn();
          $query ="SELECT qx_quirofano_programacion_id, programacion_id 
          	    FROM qx_quirofanos_programacion 
                   WHERE (hora_inicio <= timestamp '$horario' and timestamp '$horario' <= hora_fin)
                   AND quirofano_id='$quirofano' AND qx_tipo_reserva_quirofano_id != '0'";
          $result = $dbconn->Execute($query);
          if($dbconn->ErrorNo() != 0) {
               $html  = "Error al Cargar el Modulo";
               $html .= "Error DB : " . $dbconn->ErrorMsg();
               return $html;
          }else{
               $datos=$result->RecordCount();
               if($datos){
	               while(!$result->EOF){
                         $programaciones[]=$result->GetRowAssoc($ToUpper = false);
                         $result->MoveNext();
                    }
               }
          }
          
          $ObjQuirurgico = new app_Quirurgicos_user();
          
          if(sizeof($programaciones))
          {
               $html  = "          <table border=\"0\" width=\"90%\" align=\"center\">";
               $html .= "          <tr><td class=\"modulo_list_claro\">";
               $html .= "          <BR><table border=\"0\" cellspacing=\"3\" cellpadding=\"3\" width=\"95%\" align=\"center\" class=\"normal_10\">";
               $html .= "          <tr class=\"modulo_table_list_title\">";
               $quiro = $ObjQuirurgico->DescripcionQuirofano($quirofano);
               $html .= "          <td colspan=\"4\">PROGRAMACIONES CREADAS EN EL RANGO DE TIEMPO $horario EN EL ".$quiro['descripcion']."</td>";
			$html .= "          </tr>";
               for($i=0;$i<sizeof($programaciones);$i++)
               {
               	$datosReserva=$ObjQuirurgico->DatosReservaGeneral($programaciones[$i]['qx_quirofano_programacion_id']);
				if(empty($programaciones[$i]['programacion_id']))
                    {
					$html .= "      <tr class=\"modulo_list_oscuro\"><td width=\"20%\" class=\"label\">TIPO DE RESERVA</td><td colspan=\"3\">".$datosReserva[$i]['descripcion']."</td></tr>";
                         $html .= "      <tr class=\"modulo_list_oscuro\"><td width=\"20%\" class=\"label\">HORA INICIO RESERVA</td><td>".$datosReserva[$i]['hora_inicio']."</td><td width=\"20%\" class=\"label\">HORA FIN RESERVA</td><td>".$datosReserva[$i]['hora_fin']."</td></tr>";
                         if($datosReserva[$i]['qx_tipo_reserva_quirofano_id'] !=1 && $datosReserva[$i]['qx_tipo_reserva_quirofano_id']!=2)
                         {
                              $html .="     <tr class=\"modulo_list_oscuro\"><td align=\"right\" colspan=\"4\" ><a href=\"$actionCancel\"><img border=\"0\" src=\"".GetThemePath()."/images/elimina.png\"><a></td></tr>";
                         }
                         if($datosReserva[$i]['qx_tipo_reserva_quirofano_id']==1)
                         {
                              $html .= "    <tr class=\"modulo_list_oscuro\"><td colspan=\"4\">";
                              $html .= "      <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
						$html .= "      <tr class=\"modulo_table_list_title\"><td colspan=\"2\">RESPONSABLE DE LA RESERVA</td></tr>";
                              $tercero=$ObjQuirurgico->NombreTercero($datosReserva[$i]['tipo_id_tercero'],$datosReserva[$i]['tercero_id']);
						$html .= "      <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">CLIENTE</td><td>".$tercero['nombre_tercero']."</td></tr>";
                              $html .= "      <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">OBSERVACIONES</td><td>".$datosReserva[$i]['observacion']."</td></tr>";
                              $html .= "      </table><BR>";
                              $html .= "    </td></tr>";
                         }
                         if($datosReserva[$i]['qx_tipo_reserva_quirofano_id']==2)
                         {
                              $html .= "    <tr class=\"modulo_list_oscuro\"><td colspan=\"4\">";
                              $html .= "      <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
                              $html .= "      <tr class=\"modulo_table_list_title\"><td colspan=\"2\">RESPONSABLE DE LA RESERVA</td></tr>";
                              $NombrePlan=$ObjQuirurgico->PlanNombre($datosReserva[$i]['plan_id']);
                              $html .= "      <tr class=\"modulo_list_claro\"><td class=\"label\" width=\"20%\">PLAN</td><td>$NombrePlan</td></tr>";
                              $html .= "      <tr class=\"modulo_list_claro\"><td class=\"label\" width=\"20%\">OBSERVACIONES</td><td>".$datosReserva[$i]['observacionplan']."</td></tr>";
                              $html .= "      </table><BR>";
                              $html .= "    </td></tr>";
                         }
                    }
                    else
				{
                         $datosPaciente = $ObjQuirurgico->SacaDatosPacienteProgramQX($programaciones[$i]['programacion_id']);
                         $TipoId = $datosPaciente['tipo_id_paciente'];
                         $PacienteId = $datosPaciente['paciente_id'];
                         $nombreCir = $ObjQuirurgico->NombreProfesional($datosPaciente['cirujano_id'],$datosPaciente['tipo_id_cirujano']);
                         $Nombres = $ObjQuirurgico->BuscarNombresPaciente($datosPaciente['tipo_id_paciente'],$datosPaciente['paciente_id']);
                         $Apellidos = $ObjQuirurgico->BuscarApellidosPaciente($datosPaciente['tipo_id_paciente'],$datosPaciente['paciente_id']);
                         $FechaNacimiento = $ObjQuirurgico->Edad($datosPaciente['tipo_id_paciente'],$datosPaciente['paciente_id']);
                         $EdadArr = CalcularEdad($FechaNacimiento,$FechaFin);
                         $NombreResponsable = $ObjQuirurgico->Responsable($datosPaciente['plan_id']);
                         $NombrePlan = $ObjQuirurgico->PlanNombre($datosPaciente['plan_id']);
                         $diagnostico = $datosPaciente['diagnostico_nombre'];
                         $procedimientos = $ObjQuirurgico->BusquedaProcedimientosProgram($programaciones[$i]['programacion_id']);
                         $procedimientoDes = $ObjQuirurgico->DescripcionProcedimiento($procedimientos[0]['procedimiento_qx']);
                         
                         $html .= "      <tr class=\"modulo_list_oscuro\"><td colspan=\"4\">";
                         $html .= "        <BR><table border=\"0\" width=\"95%\" align=\"center\" class=\"normal_10\">";
                         $html .= "        <tr class=\"modulo_table_list_title\"><td colspan=\"4\">DATOS DE LA PROGRAMACION</td></tr>";
                         $html .= "        <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">CIRUJANO PRINCIPAL</td><td colspan=\"3\">".$nombreCir['nombre']."</td></tr>";
                         $html .= "        <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">NOMBRE DEL PACIENTE</td><td  colspan=\"3\">".$Nombres." ".$Apellidos."</td></tr>";
                         $html .= "        <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">RESPONSABLE</td><td colspan=\"3\">".$NombreResponsable."</td></tr>";
                         $html .= "        <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">PLAN</td><td colspan=\"3\">".$NombrePlan."</td></tr>";
                         $html .= "        <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">DIAGNOSTICO</td><td colspan=\"3\">".$diagnostico."</td></tr>";
                         $html .= "        <tr class=\"modulo_list_claro\"><td width=\"20%\" class=\"label\">PROCEDIMIENTO</td><td colspan=\"3\">".$procedimientoDes['descripcion']."</td></tr>";                         
                         $html .= "        </table><BR>";
                         $html .= "      </td></tr>";
				}
               }
               $html .= "          </table><BR>";
               $html .= "        </td></tr>";
               $html .= "        </table><br>";
          }
          unset($ObjQuirurgico);
		return $html;
	}
?>