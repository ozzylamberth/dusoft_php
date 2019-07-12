<?php
	/**************************************************************************************
	* $Id: JustificacionCOC.class.php,v 1.5 2011/02/17 13:21:27 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Hugo F. Manrique
	**************************************************************************************/	

	class Justificacion
	{
		var $salida = "";
		var $frec = array("Minuto(s)","Hora(s)","Dia(s)","Semana(s)");
		
		function Justificacion(){}
		/********************************************************************************
		*
		*********************************************************************************/
		function EnviarDatos()
		{
			$datos = $_REQUEST;
			$this->codigo = $_REQUEST['codigo'];
			$sw_ri = $datos['sw_riesgo_inminente'];
			if(!$sw_ri) $sw_ri = "0";
			SessionSetVar("JustificacionMedicamento",$_REQUEST);
			
			$sql .= "SELECT NEXTVAL('hc_justificaciones_no_pos_hospitala_justificacion_no_pos_id_seq') ";
			$rst = $this->ConexionBaseDatos($sql);
			
			$id = "";
			if (!$rst->EOF)
			{
				$id = $rst->fields[0];
				$rst->MoveNext();
			}
			
			list($dbconn)=GetDBConn();
			$dbconn->BeginTrans();

			$sql  = "INSERT INTO hc_justificaciones_no_pos_hospitalaria_medicamentos ( ";	
			$sql .= "			justificacion_no_pos_id,"; 	 	
			$sql .= "			ingreso,"; 	 	
			$sql .= "			codigo_producto,"; 	
			$sql .= "			usuario_id_autoriza,"; 	 	
			$sql .= "			duracion,";
			$sql .= "			dosis_dia,"; 	
			$sql .= "			justificacion,"; 		
			$sql .= "			ventajas_medicamento,";
			$sql .= "			ventajas_tratamiento,";
			$sql .= "			precauciones,";
			$sql .= "			controles_evaluacion_efectividad,";
			$sql .= "			tiempo_respuesta_esperado,";
			$sql .= "			riesgo_inminente,";
			$sql .= "			sw_riesgo_inminente,"; 	
			$sql .= "			sw_agotadas_posibilidades_existentes,";
			$sql .= "			sw_comercializacion_pais,";
			$sql .= "			sw_homologo_pos,";
			$sql .= "			descripcion_caso_clinico,";
			$sql .= "			sw_existe_alternativa_pos ";
			$sql .= "			) ";
			$sql .= "VALUES (";
			$sql .= "			 ".$id.",";
			$sql .= "			 ".SessionGetVar("IngresoHc").",";
			$sql .= "			'".$this->codigo."',";
			$sql .= "			 ".UserGetUID().",";
			$sql .= "			'".$datos['duracion_tratamiento']."',";
			$sql .= "			'".$datos['dosis_dia']."',";
			$sql .= "			'".$datos['justificacion_solicitud']."',";
			$sql .= "			'".$datos['ventajas_medicamento']."',";
			$sql .= "			'".$datos['ventajas_tratamiento']."',";
			$sql .= "			'".$datos['precauciones']."',";
			$sql .= "			'".$datos['controles_evaluacion_efectividad']."',";
			$sql .= "			'".$datos['tiempo_respuesta_esperado']."',";
			$sql .= "			'".$datos['riesgo_inminente']."',";
			$sql .= "			'".$sw_ri."',";
			$sql .= "			'".$datos['sw_agotadas_posibilidades_existentes']."',";
			$sql .= "			'".$datos['sw_comercializacion_pais']."',";
			$sql .= "			'".$datos['sw_homologo_pos']."',";
			$sql .= "			'".$datos['descripcion_caso_clinico']."',";
			$sql .= "			'".$null."'";
			$sql .= "			)";
			
               $rst = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0) 
			{
				echo "<b class=\"label\">Error al iniciar la transaccion Error DB : " . $dbconn->ErrorMsg()."</b>";
				$dbconn->RollbackTrans();
				return false;
			}
			
			$diag = SessionGetVar("diagnosticos");
			foreach($diag as $key => $diagnosticos)
			{
				$sql  = "INSERT INTO hc_justificaciones_no_pos_hospitalaria_medicamentos_diagnostico( ";
				$sql .= "				justificacion_no_pos_id,";
				$sql .= "				diagnostico_id ";
				$sql .= "				) ";
				$sql .= "VALUES ( ";
				$sql .= "			 ".$id.",";			
				$sql .= "			 '".$key."' ";			
				$sql .= "				) ";
				
				$rst = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) 
				{
					echo "<b class=\"label\">Error al iniciar la transaccion Error DB : " . $dbconn->ErrorMsg()."</b>";
					$dbconn->RollbackTrans();
					return false;
				}
			}
			
			if($datos['medicamento_pos1'] || $datos['principio_activo_pos1'])
			{
				$sm = $rs = $ci = "0";
				if($datos['sw_no_mejoria1']) $sm = "1";
				if($datos['sw_reaccion_secundaria1']) $rs = "1";
				if($datos['sw_contraindicacion1']) $ci = "1";
				
				$sql  = "INSERT INTO hc_justificaciones_no_pos_hospitalaria_medicamentos_alternativa (";
				$sql .= "				justificacion_no_pos_id,"; 	 	
				$sql .= "				alternativa_pos_id,";
				$sql .= "				medicamento_pos,";
				$sql .= "				principio_activo,";
				$sql .= "				dosis_dia_pos,";
				$sql .= "				duracion_pos,";
				$sql .= "				sw_no_mejoria,";
				$sql .= "				sw_reaccion_secundaria,";
				$sql .= "				reaccion_secundaria,";
				$sql .= "				sw_contraindicacion,";
				$sql .= "				contraindicacion,";
				$sql .= "				otras ";
				$sql .= ")";
				$sql .= "VALUES(";
				$sql .= "			 ".$id.",";
				$sql .= "			 1,";
				$sql .= "			 '".$datos['medicamento_pos1']."',";
				$sql .= "			 '".$datos['principio_activo_pos1']."',";
				$sql .= "			 '".$datos['dosis_dia_pos1']."',";
				$sql .= "			 '".$datos['duracion_tratamiento_pos1']."',";
				$sql .= "			 '".$sm."',";
				$sql .= "			 '".$rs."',";
				$sql .= "			 '".$datos['reaccion_secundaria1']."',";
				$sql .= "			 '".$ci."',";
				$sql .= "			 '".$datos['contraindicacion1']."',";
				$sql .= "			 '".$datos['otras1']."' ";
				$sql .= ")";
				
				$rst = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) 
				{
					echo "<b class=\"label\">Error al iniciar la transaccion Error DB : " . $dbconn->ErrorMsg()."</b>";
					$dbconn->RollbackTrans();
					return false;
				}
			}
			
			if($datos['medicamento_pos2'] || $datos['principio_activo_pos2'])
			{
				$sm = $rs = $ci = "0";
				if($datos['sw_no_mejoria2']) $sm = $datos['sw_no_mejoria2'];
				if($datos['sw_reaccion_secundaria2']) $rs = $datos['sw_reaccion_secundaria2'];
				if($datos['sw_contraindicacion2']) $ci = $datos['sw_contraindicacion2'];
				
				$sql = "INSERT INTO hc_justificaciones_no_pos_hospitalaria_medicamentos_alternativa (";
				$sql .= "				justificacion_no_pos_id,"; 	 	
				$sql .= "				alternativa_pos_id,";
				$sql .= "				medicamento_pos,";
				$sql .= "				principio_activo,";
				$sql .= "				dosis_dia_pos,";
				$sql .= "				duracion_pos,";
				$sql .= "				sw_no_mejoria,";
				$sql .= "				sw_reaccion_secundaria,";
				$sql .= "				reaccion_secundaria,";
				$sql .= "				sw_contraindicacion,";
				$sql .= "				contraindicacion,";
				$sql .= "				otras ";
				$sql .= ")";
				$sql .= "VALUES(";
				$sql .= "			 ".$id.",";
				$sql .= "			 2,";
				$sql .= "			 '".$datos['medicamento_pos2']."',";
				$sql .= "			 '".$datos['principio_activo_pos2']."',";
				$sql .= "			 '".$datos['dosis_dia_pos2']."',";
				$sql .= "			 '".$datos['duracion_tratamiento_pos2']."',";
				$sql .= "			 '".$sm."',";
				$sql .= "			 '".$rs."',";
				$sql .= "			 '".$datos['reaccion_secundaria2']."',";
				$sql .= "			 '".$ci."',";
				$sql .= "			 '".$datos['contraindicacion2']."',";
				$sql .= "			 '".$datos['otras2']."' ";
				$sql .= ")";
				
				$rst = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) 
				{
					echo "<b class=\"label\">Error al iniciar la transaccion Error DB : " . $dbconn->ErrorMsg()."</b>";
					$dbconn->RollbackTrans();
					return false;
				}
			}
			$dbconn->CommitTrans();
			
			$script .= "<script>\n";
			$script .= "	window.opener.document.getElementById('justificacion".$this->codigo."').innerHTML = \"<a href=\\\"javascript:Justificar('".$this->codigo."','".$id."')\\\" class='normal_10AN'>VER JUSTIFICACIÓN</a>\";";
			$script .= "	window.close();\n";
			$script .= "</script>\n";
			$this->salida .= ReturnHeader('Frecuencias',$script);
			$this->salida .= ReturnBody()."\n";	
			$this->salida .= ReturnFooter();
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ActualizarDatos()
		{
			$datos = $_REQUEST;
			$this->codigo = $datos['codigo'];
			$sw_ri = $datos['sw_riesgo_inminente'];

			if(!$sw_ri) $sw_ri = "0";			
			
			$id = $datos['justifica'];
			
			list($dbconn)=GetDBConn();
			//$dbconn->debug = true;
			$dbconn->BeginTrans();

			$sql  = "UPDATE hc_justificaciones_no_pos_hospitalaria_medicamentos  ";	
			$sql .= "SET		usuario_id_autoriza =	 ".UserGetUID().",";
			$sql .= "				duracion = '".$datos['duracion_tratamiento']."',";
			$sql .= "				dosis_dia = '".$datos['dosis_dia']."',";
			$sql .= "				justificacion = '".$datos['justificacion_solicitud']."',";
			$sql .= "				ventajas_medicamento = '".$datos['ventajas_medicamento']."',";
			$sql .= "				ventajas_tratamiento = '".$datos['ventajas_tratamiento']."',";
			$sql .= "				precauciones = '".$datos['precauciones']."',";
			$sql .= "				controles_evaluacion_efectividad = '".$datos['controles_evaluacion_efectividad']."',";
			$sql .= "				tiempo_respuesta_esperado = '".$datos['tiempo_respuesta_esperado']."',";
			$sql .= "				riesgo_inminente = '".$datos['riesgo_inminente']."',";
			$sql .= "				sw_riesgo_inminente = '".$sw_ri."',";
			$sql .= "				sw_agotadas_posibilidades_existentes = '".$datos['sw_agotadas_posibilidades_existentes']."',";
			$sql .= "				sw_comercializacion_pais = '".$datos['sw_comercializacion_pais']."',";
			$sql .= "				sw_homologo_pos = '".$datos['sw_homologo_pos']."',";
			$sql .= "				descripcion_caso_clinico = '".$datos['descripcion_caso_clinico']."',";
			$sql .= "				sw_existe_alternativa_pos =	'".$null."' ";			
			$sql .= "WHERE 	justificacion_no_pos_id = ".$id." "; 	 	
			$sql .= "AND		codigo_producto = '".$this->codigo."' "; 	
			$sql .= "AND		ingreso = ".SessionGetVar("IngresoHc")." "; 	 	

			$rst = $dbconn->Execute($sql);
			if ($dbconn->ErrorNo() != 0) 
			{
				echo "<b class=\"label\">Error al iniciar la transaccion Error DB :A " . $dbconn->ErrorMsg()."</b>";
				$dbconn->RollbackTrans();
				return false;
			}
			
			$diag1 = SessionGetVar("diagnosticos");
			$diag2 = SessionGetVar("diagnosticosA");
			foreach($diag1 as $key => $daignosticos)
			{
				if($key == $diag2[$key]['diagnostico_id'])
				{
					unset($diag1[$key]);
					unset($diag2[$key]);
				}
			}
			
			foreach($diag1 as $key => $diagnosticos)
			{
				$sql  = "INSERT INTO hc_justificaciones_no_pos_hospitalaria_medicamentos_diagnostico( ";
				$sql .= "				justificacion_no_pos_id,";
				$sql .= "				diagnostico_id ";
				$sql .= "				) ";
				$sql .= "VALUES ( ";
				$sql .= "			 ".$id.",";			
				$sql .= "			 '".$key."' ";			
				$sql .= "				) ";
				
				$rst = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) 
				{
					echo "<b class=\"label\">Error al iniciar la transaccion Error DB :X " . $dbconn->ErrorMsg()."</b>";
					$dbconn->RollbackTrans();
					return false;
				}
			}
			
			foreach($diag2 as $key => $diagnosticos)
			{
				$sql  = "DELETE FROM hc_justificaciones_no_pos_hospitalaria_medicamentos_diagnostico ";
				$sql .= "WHERE	justificacion_no_pos_id = ".$id." ";
				$sql .= "AND		diagnostico_id = '".$key."' ";
				
				$rst = $dbconn->Execute($sql);
				if ($dbconn->ErrorNo() != 0) 
				{
					echo "<b class=\"label\">Error al iniciar la transaccion Error DB : Y " . $dbconn->ErrorMsg()."</b>";
					$dbconn->RollbackTrans();
					return false;
				}
			}
			
			$alternativas = SessionGetVar("Alternativas");
			for($i = 1; $i<3; $i++)
			{
				if(($datos['medicamento_pos'.$i] || $datos['principio_activo_pos'.$i]) && sizeof($alternativas[$i])== 0)
				{
					$sm = $rs = $ci = "0";
					if($datos['sw_no_mejoria'.$i]) $sm = $datos['sw_no_mejoria'.$i];
					if($datos['sw_reaccion_secundaria'.$i]) $rs = $datos['sw_reaccion_secundaria'.$i];
					if($datos['sw_contraindicacion'.$i]) $ci = $datos['sw_contraindicacion'.$i];
					
					$sql = "INSERT INTO hc_justificaciones_no_pos_hospitalaria_medicamentos_alternativa (";
					$sql .= "				justificacion_no_pos_id,"; 	 	
					$sql .= "				alternativa_pos_id,";
					$sql .= "				medicamento_pos,";
					$sql .= "				principio_activo,";
					$sql .= "				dosis_dia_pos,";
					$sql .= "				duracion_pos,";
					$sql .= "				sw_no_mejoria,";
					$sql .= "				sw_reaccion_secundaria,";
					$sql .= "				reaccion_secundaria,";
					$sql .= "				sw_contraindicacion,";
					$sql .= "				contraindicacion,";
					$sql .= "				otras ";
					$sql .= ")";
					$sql .= "VALUES(";
					$sql .= "			 ".$id.",";
					$sql .= "			 $i,";
					$sql .= "			 '".$datos['medicamento_pos'.$i]."',";
					$sql .= "			 '".$datos['principio_activo_pos'.$i]."',";
					$sql .= "			 '".$datos['dosis_dia_pos'.$i]."',";
					$sql .= "			 '".$datos['duracion_tratamiento_pos'.$i]."',";
					$sql .= "			 '".$sm."',";
					$sql .= "			 '".$rs."',";
					$sql .= "			 '".$datos['reaccion_secundaria'.$i]."',";
					$sql .= "			 '".$ci."',";
					$sql .= "			 '".$datos['contraindicacion'.$i]."',";
					$sql .= "			 '".$datos['otras'.$i]."' ";
					$sql .= ")";
					
					$rst = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0) 
					{
						echo "<b class=\"label\">Error al iniciar la transaccion Error DB : " . $dbconn->ErrorMsg()."</b>";
						$dbconn->RollbackTrans();
						return false;
					}
				}
			}
			
			for($i = 1; $i<3; $i++)
			{
				if(($datos['medicamento_pos'.$i] || $datos['principio_activo_pos'.$i]) && sizeof($alternativas[$i]) > 0)
				{
					$sm = $rs = $ci = "0";
					if($datos['sw_no_mejoria'.$i]) $sm = $datos['sw_no_mejoria'.$i];
					if($datos['sw_reaccion_secundaria'.$i]) $rs = $datos['sw_reaccion_secundaria'.$i];
					if($datos['sw_contraindicacion'.$i]) $ci = $datos['sw_contraindicacion'.$i];
					
					$sql  = "UPDATE	hc_justificaciones_no_pos_hospitalaria_medicamentos_alternativa ";
					$sql .= "SET		medicamento_pos = '".$datos['medicamento_pos'.$i]."',";
					$sql .= "				principio_activo = '".$datos['principio_activo_pos'.$i]."',";
					$sql .= "				dosis_dia_pos = '".$datos['dosis_dia_pos'.$i]."' ,";
					$sql .= "				duracion_pos = '".$datos['duracion_tratamiento_pos'.$i]."',";
					$sql .= "				sw_no_mejoria =  '".$sm."',";
					$sql .= "				sw_reaccion_secundaria = '".$rs."',";
					$sql .= "				reaccion_secundaria = '".$datos['reaccion_secundaria'.$i]."' ,";
					$sql .= "				sw_contraindicacion = '".$ci."',";
					$sql .= "				contraindicacion =  '".$datos['contraindicacion'.$i]."',";
					$sql .= "				otras = '".$datos['otras'.$i]."' ";
					$sql .= "WHERE	justificacion_no_pos_id = ".$id." ";
					$sql .= "AND		alternativa_pos_id = $i ";
					
					$rst = $dbconn->Execute($sql);
					if ($dbconn->ErrorNo() != 0) 
					{
						echo "<b class=\"label\">Error al iniciar la transaccion Error DB : " . $dbconn->ErrorMsg()."</b>";
						$dbconn->RollbackTrans();
						return false;
					}
				}
			}
			
			$dbconn->CommitTrans();
			
			$script .= "<script>\n";
			$script .= "	window.close();\n";
			$script .= "</script>\n";
			$this->salida .= ReturnHeader('Frecuencias',$script);
			$this->salida .= ReturnBody()."\n";	
			$this->salida .= ReturnFooter();
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function Inicializar()
		{			
			$this->codigo = $_REQUEST['codigo'];
			$this->justifica = $_REQUEST['justifica'];
			$this->scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/jsrsClient.js\"></script>\n";
			$this->scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/Formulacion.js\"></script>\n";
			$this->scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/cross-browser/x/x_core.js\"></script>\n";
			$this->scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/cross-browser/x/x_drag.js\"></script>\n";
			$this->scripts .= "<script lenguage=\"javascript\" src=\"../../javascripts/cross-browser/x/x_event.js\"></script>\n";

			$this->salida .= ReturnHeader('Frecuencias',$this->scripts);
			$this->salida .= ReturnBody()."\n";	
			$this->JustificacionMedicamentos();
			$this->salida .=ReturnFooter();
		}
		/*************************************************************************
		*
		**************************************************************************/
		function JustificacionMedicamentos()
		{
			SessionSetVar("rutaimag",GetThemePath());
			$codigos = SessionGetVar("MedicamentosSeleccionados");
			SessionDelVar("diagnosticos");
			if($this->justifica)
			{
				$datos = $this->ObtenerJustificacion($this->codigo,$this->justifica);
				$this->Diagnosticos($this->justifica);
				$alterna = $this->ObtenerAlternativas($this->justifica);
			}
			else
			{
				$datos = $this->ObtenerPredetermiado($this->codigo);
			}

               $medica = $this->MedicamentosJustificados($this->codigo);
               
			$this->salida .= "<div id=\"error\" class=\"label_error\" style=\"text-align:center\"></div>\n";
			$this->salida .= "<form name=\"formajus\" action=\"javascript:EvaluarDatos(document.formajus)\" method=\"post\">\n";
			$this->salida .= "	<table  align=\"center\" border=\"0\"  width=\"97%\" class=\"modulo_table_list\">\n";
			$this->salida .= "		<tr class=\"formulacion_table_list\">\n";
               $this->salida .= "  		<td align=\"center\" colspan=\"5\">JUSTIFICACION DE MEDICAMENTOS NO POS</td>\n";
               $this->salida .= "		</tr>\n";
               $this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
               $this->salida .= "  		<td align=\"center\" class=\"normal_10AN\" width=\"35%\">PRODUCTO</td>\n";
               $this->salida .= "  		<td align=\"center\" class=\"normal_10AN\" width=\"25%\">PRINCIPIO ACTIVO</td>\n";
               $this->salida .= "  		<td align=\"center\" class=\"normal_10AN\" width=\"20%\">CONCENTRACION</td>\n";
               $this->salida .= "  		<td align=\"center\" class=\"normal_10AN\" width=\"20%\">FORMA</td>\n";
               $this->salida .= "		</tr>";
               $this->salida .= "		<tr class=\"hc_table_submodulo_list_title\">\n";
               $this->salida .= "			<td align=\"center\" class=\"normal_10AN\" >".$medica['descripcion']."</td>\n";
               $this->salida .= "			<td align=\"center\" class=\"normal_10AN\" >".$medica['principio']."</td>\n";
               $this->salida .= "			<td align=\"center\" class=\"normal_10AN\" >".$medica['cff']."</td>\n";
               $this->salida .= "			<td align=\"center\" class=\"normal_10AN\" >".$medica['forma_farma']."</td>\n";
               $this->salida .= "		</tr>";
               $this->salida .= "		<tr class=\"modulo_list_claro\">\n";
               $this->salida .= "			<td colspan = \"4\">\n";
               $this->salida .= "				<table class=\"label\">\n";
               $this->salida .= "					<tr>\n";
               $this->salida .= "						<td colspan=\"5\" width=\"80%\"align=\"left\">\n";
               $this->salida .= "							<table class=\"label\" width=\"100%\">\n";
               $this->salida .= "								<tr>\n";
               $this->salida .= "									<td width=\"30%\"align=\"left\" >DOSIS POR DIA</td>\n";
			$this->salida .= "									<td width=\"70%\" align=\"left\" >\n";
			$this->salida .= "										<input type='text' class='input-text' style=\"width:80%\" name = 'dosis_dia' value =\"".$datos['dosis_dia']."\">\n";
			$this->salida .= "									</td>\n";
			$this->salida .= "								</tr>\n";
			$this->salida .= "								<tr>\n";
			$this->salida .= "									<td width=\"30%\" align=\"left\" >DIAS DE TRATAMIENTO</td>\n";
			$this->salida .= "									<td width=\"70%\" align=\"left\" >\n";
			$this->salida .= "										<input type='text' class='input-text' style=\"width:80%\" name='duracion_tratamiento' value =\"".$datos['duracion']."\">\n";
			$this->salida .= "									</td>\n" ;
			$this->salida .= "								</tr>\n";
			$this->salida .= "							</table>\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "					</tr>\n";
               $this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
               $this->salida .= "						<td colspan=\"5\" >DIAGNOSTICO</td>\n";
               $this->salida .= "					</tr>\n";
               $this->salida .= "					<tr class=\"modulo_list_claro\" id=\"diagnosticos\">\n";
               $this->salida .= "						<td colspan = 5 align=\"center\">\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "					</tr>\n";
               $this->salida .= "					<tr class=\"modulo_list_claro\">\n";
               $this->salida .= "						<td colspan = '5' align=\"center\">\n";
			$this->salida .= "							<a href=\"javascript:IniciarB();MostrarSpan('FacturasB')\" class=\"label_error\">AGREGAR DIAGNOSTICOS</a>\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "					</tr>\n";
               $this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
               $this->salida .= "						<td colspan=\"5\" align=\"left\" >DESCRIPCION DEL CASO CLINICO</td>";
               $this->salida .= "					</tr>\n";
			$this->salida .= "					<tr>\n";
			$this->salida .= "						<td colspan = 5 align='center' >\n";
			$this->salida .= "							<textarea style =\"width:100%\" class='textarea' name = 'descripcion_caso_clinico' rows = '2'>".$datos['descripcion_caso_clinico']."</textarea>\n";
			$this->salida .= "						</td>\n";
               $this->salida .= "					</tr>\n";
               $this->salida .= "					<tr class=\"modulo_table_list_title\">";
               $this->salida .= "						<td colspan=\"5\" >ALTERNATIVAS POS PREVIAMENTE UTILIZADAS</td>";
               $this->salida .= "					</tr>\n";
			for ($j=1;$j<3;$j++)
			{
				if ($j==1)
					$ti = "PRIMERA";
				else
					$ti = "SEGUNDA";
				
				
				$this->salida .= "					<tr class=\"modulo_table_title\">";
				$this->salida .= "						<td colspan=\"5\" width=\"80%\"align=\"left\" >$ti POSIBILIDAD TERAPEUTICA POS</td>";
				$this->salida .= "					</tr>";
				$this->salida .= "					<tr>\n";
				$this->salida .= "						<td colspan=\"5\" align=\"left\">\n";
				$this->salida .= "							<table width=\"100%\">\n";
				$this->salida .= "								<tr class=\"label\">\n";
				$this->salida .= "									<td width=\"15%\" align=\"left\" >MEDICAMENTO</td>";
				$this->salida .= "									<td width=\"35%\" align=\"left\" ><input type='text' class='input-text' style=\"width:100%\" name = 'medicamento_pos$j' value=\"".$alterna[$j]['medicamento_pos']."\"></td>" ;
				$this->salida .= "									<td width=\"25%\" align=\"left\" >PRINCIPIO ACTIVO</td>";
				$this->salida .= "									<td width=\"35%\" align=\"left\" ><input type='text' class='input-text' style=\"width:100%\" name = 'principio_activo_pos$j' value=\"".$alterna[$j]['principio_activo']."\" ></td>" ;
				$this->salida .= "								</tr>\n";
				$this->salida .= "							</table>\n";
				$this->salida .= "						</td>\n";
				$this->salida .= "					</tr>\n";
				$this->salida .= "					<tr>\n";
				$this->salida .= "						<td colspan=\"5\" align=\"left\">\n";
				$this->salida .= "							<table  width=\"100%\" class=\"label\">\n";
				$this->salida .= "								<tr>\n";
				$this->salida .= "									<td width=\"18%\" align=\"left\" >DOSIS POR DIA</td>\n";
				$this->salida .= "									<td width=\"20%\" align=\"left\" ><input type='text' class='input-text' style=\"width:100%\" name = 'dosis_dia_pos$j' value=\"".$alterna[$j]['dosis_dia_pos']."\"></td>" ;
				$this->salida .= "									<td width=\"24%\" align=\"left\" >DURACION DEL TRATAMIENTO</td>";
				$this->salida .= "									<td width=\"20%\" align=\"left\" ><input type='text' class='input-text' style=\"width:100%\" name = 'duracion_tratamiento_pos$j' value=\"".$alterna[$j]['duracion_pos']."\"  ></td>" ;
				$sm = "";
				if($alterna[$j]['sw_no_mejoria'] == "1" ) $sm = "checked";
				
				$this->salida .= "									<td width=\"18%\" align=\"left\" ><input type = checkbox  name= 'sw_no_mejoria$j' value = 1 $sm>NO MEJORIA</td>";
				$this->salida .= "								</tr>\n";
				$this->salida .= "							</table>\n";
				$this->salida .= "						</td>\n";
				$this->salida .= "					</tr>\n";
				$this->salida .= "					<tr>\n";
				$this->salida .= "						<td colspan=\"5\" align=\"left\">\n";
				$this->salida .= "							<table class=\"label\">\n";
				$this->salida .= "								<tr>\n";
				$sm = "";
				if($alterna[$j]['sw_reaccion_secundaria'] == "1" ) $sm = "checked";
				
				$this->salida .= "									<td width=\"1%\"align=\"left\" ><input type = checkbox  name= 'sw_reaccion_secundaria$j' value = 1 $sm></td>";
				$this->salida .= "									<td width=\"29%\"align=\"left\" >REACCION SECUNDARIA</td>";
				$this->salida .= "									<td width=\"70%\" align='center' >\n";
				$this->salida .= "										<textarea style = \"width:100%\" class='textarea' name = 'reaccion_secundaria$j' rows ='2'>".$alterna[$j]['reaccion_secundaria']."</textarea>\n";
				$this->salida .= "									</td>\n";
				$this->salida .= "								</tr>\n";
				$this->salida .= "								<tr>\n";
				$sm = "";
				if($alterna[$j]['sw_contraindicacion'] == "1" ) $sm = "checked";

				$this->salida .= "									<td width=\"1%\"align=\"left\" >\n";
				$this->salida .= "										<input type = checkbox name= 'sw_contraindicacion$j' value = 1 $sm>\n";
				$this->salida .= "									</td>\n";
				$this->salida .= "									<td width=\"29%\"align=\"left\" >CONTRAINDICACION EXPRESA</td>\n";

				$this->salida .= "									<td width=\"70%\" align='center' >\n";
				$this->salida .= "										<textarea style =\"width:100%\" class='textarea' name = 'contraindicacion$j' rows = '2'>".$alterna[$j]['contraindicacion']."</textarea>\n";
				$this->salida .= "									</td>\n" ;
				$this->salida .= "								</tr>\n";
				$this->salida .= "								<tr>\n";
				$this->salida .= "									<td width=\"30%\" style=\"text-indent:20pt\" colspan=\"2\">OTRAS</td>\n";
				$this->salida .= "									<td width=\"70%\" align='center'>\n";
				$this->salida .= "										<textarea style = \"width:100%\" class='textarea' name = 'otras$j' rows = '2'>".$alterna[$j]['otras']."</textarea>\n";
				$this->salida .= "									</td>\n" ;
				$this->salida .= "								</tr>\n";
				$this->salida .= "							</table>\n";
				$this->salida .= "						</td>\n";
				$this->salida .= "					</tr>\n";
			}	
      
			$this->salida .= "					<tr class=\"modulo_table_list_title\">\n";
               $this->salida .= "						<td colspan=\"5\">CRITERIOS DE JUSTIFICACION</td>";
               $this->salida .= "					</tr>\n";
               $this->salida .= "					<tr >";
               $this->salida .= "						<td colspan=\"5\" align=\"center\">";
               $this->salida .= "							<table class=\"label\" align=\"center\" border=\"0\"  width=\"100%\">";
               $this->salida .= "								<tr>\n";
               $this->salida .= "									<td>JUSTIFICACION DE LA SOLICITUD:</td>";
               $this->salida .= "								</tr>\n";
               $this->salida .= "								<tr>\n";
               $this->salida .= "									<td>\n";
			$this->salida .= "										<textarea style = \"width:100%\" class='textarea' name = 'justificacion_solicitud' rows = '2'>".$datos['justificacion']."</textarea>\n";
			$this->salida .= "									</td>\n";
			$this->salida .= "								</tr>\n";
			$this->salida .= "								<tr>\n";
			$this->salida .= "									<td>VENTAJAS DE ESTE MEDICAMENTO:</td>";
			$this->salida .= "								</tr>\n";
			$this->salida .= "								<tr>\n";
			$this->salida .= "									<td ><textarea style = \"width:100%\" class='textarea' name = 'ventajas_medicamento' rows ='2'>".$datos['ventajas_medicamento']."</textarea></td>" ;
			$this->salida .= "								</tr>\n";
			$this->salida .= "								<tr>\n";
			$this->salida .= "									<td >VENTAJAS DEL TRATAMIENTO:</td>";
			$this->salida .= "								</tr>\n";
			$this->salida .= "								<tr>\n";
			$this->salida .= "									<td><textarea style = \"width:100%\" class='textarea' name = 'ventajas_tratamiento' rows = '2'>".$datos['ventajas_tratamiento']."</textarea></td>" ;
			$this->salida .= "								</tr>\n";
			$this->salida .= "								<tr>\n";
			$this->salida .= "									<td>PRECAUCIONES:</td>\n";
			$this->salida .= "								</tr>\n";
			$this->salida .= "								<tr>\n";
               $this->salida .= "									<td><textarea style = \"width:100%\" class='textarea' name = 'precauciones' rows = '2'>".$datos['precauciones']."</textarea></td>" ;
               $this->salida .= "								</tr>\n";
			$this->salida .= "								<tr>\n";
			$this->salida .= "									<td>CONTROLES PARA EVALUAR LA EFECTIVIDAD DEL MEDICAMENTO:</td>";
			$this->salida .= "								</tr>\n";
			$this->salida .= "								<tr>\n";
			$this->salida .= "									<td><textarea style = \"width:100%\" class='textarea' name = 'controles_evaluacion_efectividad' 60 rows ='2'>".$datos['controles_evaluacion_efectividad']."</textarea></td>" ;
			$this->salida .= "								</tr>\n";
			$this->salida .= "							</table>\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "					</tr>\n";
			$this->salida .= "					<tr >\n";
			$this->salida .= "						<td colspan=\"5\" align=\"left\">\n";
			$this->salida .= "							<table class=\"label\" width=\"100%\">\n";
			$this->salida .= "								<tr>\n";
			$this->salida .= "									<td width=\"40%\" align=\"left\" >TIEMPO DE RESPUESTA ESPERADO</td>";
			$this->salida .= "									<td width=\"60%\" align=\"left\" ><input type='text' class='input-text' size = 20 name = 'tiempo_respuesta_esperado'   value =\"".$datos['tiempo_respuesta_esperado']."\"></td>" ;
			$this->salida .= "								</tr>\n";
			$chk = "";
			($datos['sw_riesgo_inminente'] == '1')? $chk="checked":$chk; 
			$this->salida .= "								<tr>\n";
               $this->salida .= "									<td width=\"40%\"align=\"left\" ><input type = checkbox name= 'sw_riesgo_inminente' value='1' $chk >RIESGO INMINENTE</td>";
               $this->salida .= "									<td width=\"60%\" align='center' ><textarea style = \"width:100%\" class='textarea' name = 'riesgo_inminente' rows = '2'>".$datos['riesgo_inminente']."</textarea></td>" ;
			$this->salida .= "								</tr>\n";
			$this->salida .= "							</table>\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "					</tr>\n";
			$this->salida .= "					<tr>\n";
			$cer = "";$uno = "checked";
			if($datos['sw_agotadas_posibilidades_existentes'] == '0' || !$datos['sw_agotadas_posibilidades_existentes'])
			{
				$cer = "checked"; $uno = "";
			}
			$this->salida .= "						<td colspan=\"5\" align=\"left\">\n";
			$this->salida .= "							<table class=\"label\" width=\"%\">\n";
			$this->salida .= "								<tr>\n";
			$this->salida .= "									<td colspan=\"1\" align=\"left\" >SE HAN AGOTADO LAS POSIBILIDADES EXISTENTES:</td>\n";
			$this->salida .= "									<td colspan=\"1\" align=\"left\" ><input type = radio name= 'sw_agotadas_posibilidades_existentes' value = '1' $uno>&nbsp; SI</td>\n";
			$this->salida .= "									<td colspan=\"1\" align=\"left\" ><input type = radio name= 'sw_agotadas_posibilidades_existentes' value = '0' $cer>&nbsp; NO</td>\n";
			$this->salida .= "								</tr>\n";
			
			$cer = "";$uno = "checked";
			if($datos['sw_homologo_pos'] == '0' || !$datos['sw_agotadas_posibilidades_existentes'])
			{
				$cer = "checked"; $uno = "";
			}
			$this->salida .= "								<tr>\n";
			$this->salida .= "									<td colspan=\"1\" align=\"left\" >TIENE HOMOLOGO EN EL POS:</td>\n";
			$this->salida .= "									<td colspan=\"1\" align=\"left\" ><input type = radio name= 'sw_homologo_pos' value = '1' $uno>&nbsp; SI</td>\n";
			$this->salida .= "									<td colspan=\"1\" align=\"left\" ><input type = radio name= 'sw_homologo_pos' value = '0' $cer>&nbsp; NO</td>\n";
			$this->salida .= "								</tr>\n";
			
			$cer = "";$uno = "checked";
			if($datos['sw_comercializacion_pais'] == '0' || !$datos['sw_agotadas_posibilidades_existentes'])
			{
				$cer = "checked"; $uno = "";
			}
			$this->salida .= "								<tr>";
			$this->salida .= "									<td colspan=\"1\" align=\"left\" >ES COMERCIALIZADO EN EL PAIS:</td>";
			$this->salida .= "									<td colspan=\"1\" align=\"left\" ><input type = radio name= 'sw_comercializacion_pais' value = '1' $uno>&nbsp; SI</td>";
			$this->salida .= "									<td colspan=\"1\" align=\"left\" ><input type = radio name= 'sw_comercializacion_pais' value = '0' $cer>&nbsp; NO</td>";
			$this->salida .= "								</tr>";
			$this->salida .= "							</table>";
			$this->salida .= "						</td>";
			$this->salida .= "					</tr>";
			/*$this->salida .= "					<tr class=\"modulo_table_title\">";
			$this->salida .= "						<td colspan=\"5\" width=\"80%\"align=\"left\" >NOTA</td>";
			$this->salida .= "					</tr>";
			$this->salida .= "					<tr class=\"normal_10A\">\n";
			$this->salida .= "						<td colspan=\"5\" width=\"80%\"align=\"justify\" >\n";
			$this->salida .= "							Para el trámite de esta solicitud es obligatorio el diligenciamiento ";
			$this->salida .= "							completo, anexando el original de la formula médica y el resumen de la ";
			$this->salida .= "							historia clinica.<br>La entrega del medicamento está sujeta";
			$this->salida .= "							a la aprobación del comité técnico-cientifico, de acuerdo a lo establecido ";
			$this->salida .= "							en la resolución 5061 del 23 de diciembre de 1997.\n";
			$this->salida .= "						</td>";
			$this->salida .= "					</tr>";*/
			$this->salida .= "				</table><br>";
			$this->salida .= "				<table align=\"center\" border=\"0\"  width=\"80%\">\n";
			$this->salida .= "					<tr>\n";
			$this->salida .= "						<td align=\"center\"><input class=\"input-submit\" name= 'guardar_justificacion' type=\"submit\" value=\"Guardar Justificación\"></td>";
			$this->salida .= "						<td align=\"left\">\n";
			$this->salida .= "							<input class=\"input-submit\" name= 'cancelar' type=\"button\" value=\"Cerrar\" onclick=\"window.close()\">\n";
			$this->salida .= "						</td>\n";
			$this->salida .= "					</tr>\n";
			$this->salida .= "				</table>\n";
			$this->salida .= "			</td>\n";
			$this->salida .= "		</tr>\n";
			$this->salida .= "	</table>\n";
			$this->salida .= "</form>\n";
			$this->salida .= "<script>\n";
			$this->salida .= "	var contenedor = 'Contenedor';\n";
			$this->salida .= "	var titulo = 'titulo';\n";
			$this->salida .= "	var hiZ = 2;\n";
			$this->salida .= "	function IniciarB()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		contenedor = 'FacturasB';\n";
			$this->salida .= "		titulo = 'titulob';\n";
			$this->salida .= "		ele = xGetElementById('ContenidoB');\n";
			$this->salida .= "	  xResizeTo(ele,550,360);\n";
			$this->salida .= "		ele = xGetElementById(contenedor);\n";
			$this->salida .= "	  xResizeTo(ele,550, 'auto');\n";
			$this->salida .= "	  xMoveTo(ele, 10, xScrollTop()+10);\n";
			$this->salida .= "		ele = xGetElementById(titulo);\n";
			$this->salida .= "	  xResizeTo(ele,530, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrarb');\n";
			$this->salida .= "	  xResizeTo(ele,20, 20);\n";
			$this->salida .= "		xMoveTo(ele, 530, 0);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function IniciarM()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		contenedor = 'MedicamentosB';\n";
			$this->salida .= "		titulo = 'tituloM';\n";
			$this->salida .= "		ele = xGetElementById('ContenidoM');\n";
			$this->salida .= "	  xResizeTo(ele,600,360);\n";
			$this->salida .= "		ele = xGetElementById(contenedor);\n";
			$this->salida .= "	  xResizeTo(ele,600, 'auto');\n";
			$this->salida .= "	  xMoveTo(ele, 10, xScrollTop()+10);\n";
			$this->salida .= "		ele = xGetElementById(titulo);\n";
			$this->salida .= "	  xResizeTo(ele,580, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrarM');\n";
			$this->salida .= "	  xResizeTo(ele,20, 20);\n";
			$this->salida .= "		xMoveTo(ele, 580, 0);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function OcultarSpan(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		try\n";
			$this->salida .= "		{\n";
			$this->salida .= "			e = xGetElementById(Seccion);\n";
			$this->salida .= "			e.style.display = \"none\";\n";
			$this->salida .= "		}\n";
			$this->salida .= "		catch(error){}\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function MostrarTitle(Seccion)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		xShow(Seccion);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function OcultarTitle(Seccion)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		xHide(Seccion);\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function MostrarSpan(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		try\n";
			$this->salida .= "		{\n";
			$this->salida .= "			e = xGetElementById(Seccion);\n";
			$this->salida .= "			e.style.display = \"\";\n";
			$this->salida .= "		}\n";
			$this->salida .= "		catch(error){alert(error)}\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function myOnDragStart(ele, mx, my)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  window.status = '';\n";
			$this->salida .= "	  if (ele.id == titulo) xZIndex(contenedor, hiZ++);\n";
			$this->salida .= "	  else xZIndex(ele, hiZ++);\n";
			$this->salida .= "	  ele.myTotalMX = 0;\n";
			$this->salida .= "	  ele.myTotalMY = 0;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function myOnDrag(ele, mdx, mdy)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  if (ele.id == titulo) {\n";
			$this->salida .= "	    xMoveTo(contenedor, xLeft(contenedor) + mdx, xTop(contenedor) + mdy);\n";
			$this->salida .= "	  }\n";
			$this->salida .= "	  else {\n";
			$this->salida .= "	    xMoveTo(ele, xLeft(ele) + mdx, xTop(ele) + mdy);\n";
			$this->salida .= "	  }  \n";
			$this->salida .= "	  ele.myTotalMX += mdx;\n";
			$this->salida .= "	  ele.myTotalMY += mdy;\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function myOnDragEnd(ele, mx, my)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	}\n";
			$this->salida .= "	var dias = '';\n";
			$this->salida .= "	function EvaluarDatos(objeto)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		dias = objeto.duracion_tratamiento.value;\n";
			$this->salida .= "		jsrsExecute(\"ScriptsRemotos/justificacion.php\",Evaluar,\"ObtenerLongitud\",new Array());\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function Evaluar(longitud)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		if(dias == \"\")\n";
			$this->salida .= "			xGetElementById('error').innerHTML = 'FALTA INGRESAR LOS DIAS DE TRATAMIENTO';\n";
			$this->salida .= "			else if(longitud == \"0\")\n";
			$this->salida .= "				xGetElementById('error').innerHTML = 'FALTA INGRESAR EL DIAGNOSTICO';\n";
			$this->salida .= "				else\n";
			$this->salida .= "				{\n";
			if($this->justifica)
				$this->salida .= "					document.formajus.action = \"Justificacion.class.php?metodo=2&codigo=".$this->codigo."&justifica=".$this->justifica."\";";
			else
				$this->salida .= "					document.formajus.action = \"Justificacion.class.php?metodo=1&codigo=".$this->codigo."\";";
			
			$this->salida .= "					document.formajus.submit();";
			$this->salida .= "				}\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function Buscar(objeto,buscador)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		datos = new Array();\n";
			$this->salida .= "		datos[0] = objeto.producto\n";
			$this->salida .= "		datos[1] = objeto.principio_activo\n";
			$this->salida .= "		datos[2] = pagina;\n";
			$this->salida .= "		jsrsExecute(\"ScriptsRemotos/justificacion.php\",Mostrar,\"BuscarMedicamentos\",new Array());\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function Mostrar(html)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		xGetElementById('resultado').innerHTML = html;\n";
			$this->salida .= "	}\n";
			$this->salida .= "</script>\n";
			$this->salida .= "<div id='FacturasB' class='d2Container' style=\"display:none\">\n";
			$this->salida .= "	<div id='titulob' class='draggable' style=\"	text-transform: uppercase;text-align:center\">BUSCADOR DE DIAGNOSTICOS</div>\n";
			$this->salida .= "	<div id='cerrarb' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('FacturasB')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='ContenidoB' class='d2Content' style=\"background:#EFEFEF\"><br>\n";
			$this->salida .= "		<form name=\"buscadorfacturas\" action=\"javascript:CrearVariables(document.buscadorfacturas,'1')\" >\n";
			$this->salida .= "			<table width=\"100%\" align=\"center\">\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td align=\"center\" class=\"label\">\n";
			$this->salida .= "						<table class=\"modulo_table_list\" width=\"90%\">\n";
			$this->salida .= "							<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "								<td colspan=\"5\">BUSQUEDA DE DIAGNOSTICOS</td>\n";
			$this->salida .= "							</tr>\n";
			$this->salida .= "							<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "								<td style=\"text-indent:8pt;text-align:left\">CODIGO</td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">\n";
			$this->salida .= "									<input type=\"text\" class=\"input-text\" name=\"codigo\" size=\"10\" maxlength=\"10\" >\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "								<td style=\"text-indent:8pt;text-align:left\">DIAGNOSTICO</td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">\n";
			$this->salida .= "									<input type=\"text\" class=\"input-text\" name=\"diagnostico\" size=\"30\" maxlength=\"10\" >\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "								<td class=\"modulo_list_claro\">\n";
			$this->salida .= "									<input type=\"submit\" class=\"input-submit\" name=\"buscar\" value=\"Buscar\">\n";
			$this->salida .= "								</td>\n";
			$this->salida .= "							</tr>\n";
			$this->salida .= "						</table>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td>\n";
			$this->salida .= "						<div id=\"resultado\">\n";
			$this->salida .= "							<table align=\"center\">\n";
			$this->salida .= "								<tr><td height=\"25\"><a href=\"javascript:OcultarSpan('FacturasB')\" class=\"label_error\">CERRAR</a></td></tr>\n";
			$this->salida .= "							</table>\n";
			$this->salida .= "						</div>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "</div>\n";
			
			$this->salida .= "<div id='MedicamentosB' class='d2Container' style=\"display:none\">\n";
			$this->salida .= "	<div id='tituloM' class='draggable' style=\"	text-transform: uppercase;text-align:center\">BUSCADOR DE MEDICAMENTOS</div>\n";
			$this->salida .= "	<div id='cerrarM' class='draggable'><a class=\"hcPaciente\" href=\"javascript:OcultarSpan('MedicamentosB')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='ContenidoM' class='d2Content' style=\"background:#EFEFEF\"><br>\n";
			$this->salida .= "	<form name=\"buscador\" action=\"javascript:Buscar(document.buscador,'1')\" method=\"post\">\n";
			$this->salida .= "		<table align=\"center\" border=\"0\" width=\"95%\" class=\"modulo_table_list\">\n";
			$this->salida .= "			<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "				<td align=\"center\" colspan=\"7\">BUSCAR MEDICAMENTOS </td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "			<tr class=\"hc_table_submodulo_list_title\">\n";		
			$this->salida .= "				<td width=\"%\" class=\"normal_10AN\">PRODUCTO:</td>\n";
			$this->salida .= "				<td width=\"%\" align='center'>\n";
			$this->salida .= "					<input type='text' class='input-text' style=\"width:100%\" name = 'producto' value =\"".$producto."\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "				<td width=\"%\" class=\"normal_10AN\">PRINCIPIO ACTIVO:</td>";
			$this->salida .= "				<td width=\"%\" align='center' >\n";
			$this->salida .= "					<input type='text' class='input-text' style=\"width:100%\" name = 'principio_activo' value =\"".$principio_activo."\" >\n";
			$this->salida .= "				</td>\n" ;
			$this->salida .= "				<td width=\"%\" align=\"center\">\n";
			$this->salida .= "					<input class=\"input-submit\" name=\"buscar\" type=\"submit\" value=\"Buscar\">\n";
			$this->salida .= "				</td>\n";
			$this->salida .= "			</tr>\n";
			$this->salida .= "		</table>\n";
			$this->salida .= "	</form>\n";
			$this->salida .= "	<div name=\"resultado\" id=\"resultado\" style=\"display:none;border:1px solid #AFAFAF;\"></div>\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "</div>\n";
			
			if(sizeof(SessionGetVar("diagnosticos")) > 0)
			{
				$this->salida .= "<script>";
				$this->salida .= "	AgregarDiagnostico('-1',false);";
				$this->salida .= "</script>";
			}
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerJustificacion($medicamento,$justificacion)
		{
			$sql .= "SELECT	justificacion_no_pos_id,"; 	 	
			$sql .= "				ingreso,"; 	 	
			$sql .= "				codigo_producto,"; 	
			$sql .= "				usuario_id_autoriza,"; 	 	
			$sql .= "				duracion,";
			$sql .= "				dosis_dia,"; 	
			$sql .= "				justificacion,"; 		
			$sql .= "				ventajas_medicamento,";
			$sql .= "				ventajas_tratamiento,";
			$sql .= "				precauciones,";
			$sql .= "				controles_evaluacion_efectividad,";
			$sql .= "				tiempo_respuesta_esperado,";
			$sql .= "				riesgo_inminente,";
			$sql .= "				sw_riesgo_inminente,"; 	
			$sql .= "				sw_agotadas_posibilidades_existentes,";
			$sql .= "				sw_comercializacion_pais,";
			$sql .= "				sw_homologo_pos,";
			$sql .= "				descripcion_caso_clinico,";
			$sql .= "				sw_existe_alternativa_pos ";
			$sql .= "FROM		hc_justificaciones_no_pos_hospitalaria_medicamentos  ";	
			$sql .= "WHERE	justificacion_no_pos_id = ".$justificacion." ";
			$sql .= "AND		codigo_producto = '".$medicamento."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerPredetermiado($medicamento)
		{
			$sql .= "SELECT	justificacion,"; 		
			$sql .= "				ventajas_medicamento,";
			$sql .= "				ventajas_tratamiento,";
			$sql .= "				precauciones,";
			$sql .= "				controles_evaluacion_efectividad,";
			$sql .= "				tiempo_respuesta_esperado,";
			$sql .= "				riesgo_inminente,";
			$sql .= "				sw_riesgo_inminente,"; 	
			$sql .= "				sw_agotadas_posibilidades_existentes,";
			$sql .= "				sw_comercializacion_pais,";
			$sql .= "				sw_homologo_pos ";
			$sql .= "FROM		hc_justificaciones_no_pos_plantillas  ";	
			$sql .= "WHERE	codigo_medicamento = '".$medicamento."' ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ObtenerAlternativas($justificacion)
		{
			$sql .= "SELECT	justificacion_no_pos_id,"; 	 	
			$sql .= "				alternativa_pos_id,";
			$sql .= "				medicamento_pos,";
			$sql .= "				principio_activo,";
			$sql .= "				dosis_dia_pos,";
			$sql .= "				duracion_pos,";
			$sql .= "				sw_no_mejoria,";
			$sql .= "				sw_reaccion_secundaria,";
			$sql .= "				reaccion_secundaria,";
			$sql .= "				sw_contraindicacion,";
			$sql .= "				contraindicacion,";
			$sql .= "				otras ";
			$sql .= "FROM 	hc_justificaciones_no_pos_hospitalaria_medicamentos_alternativa ";
			$sql .= "WHERE justificacion_no_pos_id = ".$justificacion." ";
			
               if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[1]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$rst->MoveNext();
			SessionSetVar("Alternativas",$datos);
			return $datos;
		}
          /********************************************************************************
		*
		*********************************************************************************/
          function MedicamentosJustificados($codigo)
		{
               list($dbconn) = GetDBconn();
               $sql = "SELECT A.descripcion, 
                              B.cod_principio_activo, B.concentracion_forma_farmacologica AS cff, 
                              B.cod_forma_farmacologica,
                              C.descripcion AS principio,
                              D.descripcion AS forma_farma
                    FROM   inventarios_productos AS A,
                              medicamentos AS B,
                              inv_med_cod_principios_activos AS C,
                              inv_med_cod_forma_farmacologica AS D
                    WHERE  A.codigo_producto = '".$codigo."'
                    AND    A.codigo_producto = B.codigo_medicamento
                    AND    B.cod_principio_activo = C.cod_principio_activo
                    AND    B.cod_forma_farmacologica = D.cod_forma_farmacologica;";
			
               if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			return $datos;
		}
     

          /********************************************************************************
		*
		*********************************************************************************/
		function Diagnosticos($justificacion)
		{
			$sql  = "SELECT DG.diagnostico_id,";
			$sql .= " 			DG.diagnostico_nombre ";
			$sql .= "FROM 	diagnosticos DG,";
			$sql .= "				hc_justificaciones_no_pos_hospitalaria_medicamentos_diagnostico HJ ";
			$sql .= "WHERE	HJ.justificacion_no_pos_id = ".$justificacion." ";
			$sql .= "AND		HJ.diagnostico_id = DG.diagnostico_id ";
					
			if(!$rst = $this->ConexionBaseDatos($sql))
				return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			SessionSetVar("diagnosticos",$datos);
			SessionSetVar("diagnosticosA",$datos);
			return $datos;
		}
		/********************************************************************************
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*********************************************************************************/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			//$dbconn->debug = true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				echo $this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
	}
	/*********************************************************************************/
	$VISTA='HTML';
	$_ROOT='../../';
	include $_ROOT.'includes/enviroment.inc.php';
		
	$fileName = "themes/$VISTA/" . GetTheme() . "/module_theme.php";
	IncludeFile($fileName);

	$justifica = new Justificacion();
	if($_REQUEST['metodo'] == '1')
		$justifica->EnviarDatos();
	else if($_REQUEST['metodo'] == '2')
		$justifica->ActualizarDatos();
	else
		$justifica->Inicializar();
	echo $justifica->salida; 
?>