<?php
	/**
	* $Id: medicamentos.php,v 1.4 2011/02/17 13:21:27 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	* 
	* @author Hugo F. Manrique
	*/	
	$VISTA = "HTML";
	$_ROOT = "../../../";
	include  "../../../classes/rs_server/rs_server.class.php";
	include	 "../../../includes/enviroment.inc.php";
	class procesos_admin extends rs_server
	{
		/**
		*
		*/
		function ActualizarFormulacion($arreglo)
		{
			$html = "";
			$result = true;
			$result = $this->IngresoMedicamento($arreglo);
			
			if($result)
			{
				$est0 = "style=\"text-indent:2pt;text-align:left;font-size:11px;\" ";
				$est1 = "style=\"text-indent:2pt;text-align:left;font-size:9px;\" ";
				$estilos = "style=\"border-bottom-width:0px;border-left-width:1px;border-right-width:0px;border-top-width:0px;border-style: solid;\""; 

				$clasesjs .= "new Array('formulacion_table_list_suspendido','formulacion_table_list',";
				$clasesjs .= "'formulacion_table_list_oscuro','formulacion_table_list_claro','label','label2')";
				
				$datos = $this->ConsultaMedicamento($arreglo[0]); 
				
				//$html .= "<div id=\"CapaFormula".$arreglo[7]."\">\n";
				$html .= "	<table id=\"Bordex".$arreglo[7]."\" align=\"center\" border=\"0\" width=\"100%\" class=\"formulacion_table_list_oscuro\">\n";
				$html .= "		<tr id=\"Formulacion0x".$arreglo[7]."\" class=\"formulacion_table_list\">\n";
				$html .= "  		<td width=\"84%\">\n";
				$html .= "				<table id=\"Formulacion1x".$arreglo[7]."\" class=\"formulacion_table_list\" >\n";
				$html .= "					<tr >\n";
				$html .= "						<td $est0 >".$datos['producto']."</td>\n";
				$html .= "						<td id=\"Formulacion2x".$arreglo[7]."\" $est1> (".$datos['principio_activo'].")</td>\n";
				$html .= "					</tr>\n";
				$html .= "				</table>\n";
				$html .= "			</td>\n";
				$html .= "			<td width=\"4%\" align=\"center\" >\n";
				$html .= "				<a href=\"javascript:EditarFormulacion('".$datos['codigo_producto']."','".$arreglo[7]."',".$arreglo[9].")\"  title=\"EDITAR\">\n";
				$html .= "					<img name =\"Editar\" height=\"18\" src=\"".$arreglo[8]."/images/edita.png\" border=\"0\" >\n";
				$html .= "				</a>\n";
				$html .= "			</td>\n";
				$html .= "			<td width=\"4%\" align=\"center\">\n";
				$html .= "				<a href=\"javascript:VisualizarHistorial('".$datos['codigo_producto']."')\"  title=\"HISTORIAL\">\n";
				$html .= "					<img name =\"Historial".$arreglo[7]."\" height=\"18\"  src=\"".$arreglo[8]."/images/HistoriaClinica1/historia_actual_osc.gif\" border=\"0\">\n";
				$html .= "				</a>\n";
				$html .= "			</td>\n";
				$html .= "			<td width=\"4%\" align=\"center\">\n";
				$html .= "				<a href=\"javascript:DatosActuales('".$arreglo[7]."',$clasesjs,'".$datos['codigo_producto']."',".$arreglo[9].");Iniciar('".$datos['producto']."');\" >\n";
				$html .= "					<img width=\"16\" height=\"18\" title=\"SUSPENDER MEDICAMENTO\" src=\"".$arreglo[8]."/images/pactivo.png\" border=\"0\" name=\"Suspender".$arreglo[7]."\" >\n";
				$html .= "				</a>\n";
				$html .= "			</td>\n";
				$html .= "			<td width=\"4%\" align=\"center\" >\n";
				$html .= "				<a href=\"javascript:Finalizar('".$datos['codigo_producto']."','".$arreglo[7]."',".$arreglo[9].",'".$datos['producto']."')\"  title=\"FINALIZAR MEDICAMENTO\">\n";
				$html .= "					<img name =\"Finalizar\" height=\"18\" src=\"".$arreglo[8]."/images/HistoriaClinica1/cerrar_claro.gif\" border=\"0\" >\n";
				$html .= "				</a>\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				$html .= "		<tr class=\"modulo_list_claro\">\n";
				$html .= "			<td colspan=\"5\">\n";
				$html .= "				<table width=\"100%\">\n";
				$html .= "					<tr>\n";
				$html .= "						<td width=\"60%\" valign=\"top\">\n";
				$html .= "							<table id=\"Formulacion3x1".$arreglo[7]."\" class=\"label\" >\n";
				$html .= "								<tr>\n";
				$html .= "									<td >VIA DE ADMINISTRACIÓN: </td>\n";
				$html .= "									<td colspan=\"2\">".$datos['nombre']."</td>\n";
				$html .= "								</tr>\n";
				$html .= "								<tr >\n";
				$html .= "									<td >DOSIS</td>\n";
				$html .= "									<td align=\"right\">".$datos['dosis']."</td><td>".$datos['unidad_dosificacion']."</td>\n";
				$html .= "									<td align=\"left\">".$datos['frecuencia']."</td>\n";
				$html .= "								</tr>\n";				
				$html .= "								<tr >\n";
				$html .= "									<td >CANTIDAD</td>\n";
				$html .= "									<td align=\"right\">".$datos['cantidad']."</td><td>".$datos['umm']."</td>\n";
				$html .= "								</tr>\n";
        if($datos['dias_tratamiento'])
        {
          $html .= "								<tr >\n";
          $html .= "									<td >DIAS TRATAMIENTO</td>\n";
          $html .= "									<td align=\"right\">".intval($datos['dias_tratamiento'])."</td>\n";
          $html .= "                  <td colspan=\"2\"></td>\n";
          $html .= "								</tr>\n";
				}
        $html .= "							</table>\n";
				$html .= "						</td>\n";
				$html .= "						<td width=\"40%\" valign=\"top\" $estilos>\n";
				$html .= "							<table align=\"center\" id=\"Formulacion3x2".$arreglo[7]."\" class=\"label\" width=\"98%\">\n";
				$html .= "								<tr>\n";
				$html .= "									<td align=\"left\">FORMULÓ: <font style=\"font-weight:normal;\">".$datos['med_formula']."</font></td>\n";
				$html .= "								</tr>\n";
				if($datos['med_modifica'] != $datos['med_formula'])
				{
					$html .= "								<tr>\n";
					$html .= "									<td align=\"left\">MODIFICO: <font style=\"font-weight:normal;\">".$datos['med_modifica']."</font></td>\n";
					$html .= "								</tr>\n";
				}
				
				$usuariohc = UserGetUID();
				if($datos['sw_confirmacion_formulacion'] == '0' && $datos['usuario_id'] == $usuariohc)
				{
					$html .= "								<tr>\n";
					$html .= "									<td align=\"center\"><a href=\"\" class=\"normal_10AN\">CONFIRMAR</a></td>\n";
					$html .= "								</tr>\n";
				}
				
					if($datos['sw_requiere_autorizacion_no_pos'] == 'S' && !$datos['justificacion_no_pos_id'])
					{
						$html .= "								<tr>\n";
						$html .= "									<td id=\"justificacion".$datos['codigo_producto']."\" align=\"center\"><a href=\"javascript:Justificar('".$datos['codigo_producto']."','".$datos['justificacion_no_pos_id']."')\" class=\"normal_10AN\">JUSTIFICAR</a></td>\n";
						$html .= "								</tr>\n";					
					}
					else if($datos['sw_requiere_autorizacion_no_pos'] == 'S' && $datos['justificacion_no_pos_id'])
						{
							$html .= "								<tr>\n";
							$html .= "									<td id=\"justificacion".$datos['codigo_producto']."\" align=\"center\"><a href=\"javascript:Justificar('".$datos['codigo_producto']."','".$datos['justificacion_no_pos_id']."')\" class=\"normal_10AN\">VER JUSTIFICACIÓN</a></td>\n";
							$html .= "								</tr>\n";					
						}
						else if($datos['sw_requiere_autorizacion_no_pos'] == 'P' )
							{
								$html .= "								<tr>\n";
								$html .= "									<td id=\"justificacion".$datos['codigo_producto']."\" align=\"center\"><b class=\"normal_10AN\">MEDICAMENTO NO POS A PETICION DEL PACIENTE</B></td>\n";
								$html .= "								</tr>\n";					
							}
							else if($datos['sw_requiere_autorizacion_no_pos'] == 'N' )
								{
									$html .= "								<tr>\n";
									$html .= "									<td id=\"justificacion".$datos['codigo_producto']."\" align=\"center\"><b class=\"normal_10AN\">MEDICAMENTO POS</b></td>\n";
									$html .= "								</tr>\n";					
								}	
				
				$html .= "							</table>\n";
				$html .= "						</td>\n";
				$html .= "					</tr>\n";	
				$html .= "				</table>\n";
				$html .= "			</td>\n";
				$html .= "		</tr>\n";
				if($datos['observacion'] != "")
				{
					$html .= "		<tr class=\"modulo_list_claro\">\n";
					$html .= "			<td colspan=\"5\">\n";
					$html .= "				<table width=\"100%\" id=\"Formulacion5x".$arreglo[7]."\" class=\"label\">\n";
					$html .= "					<tr>\n";
					$html .= "						<td valign=\"top\" width=\"30%\">\n";
					$html .= "							OBSERVACIONES E INDICACIONES DE SUMINISTRO</td>\n";
					$html .= "						</td>\n";
					$html .= "						<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
					$html .= "							".$datos['observacion']."\n";
					$html .= "						</td>\n";
					$html .= "					</tr>\n";
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
				}
				$html .= "	</table><br>";
				//$html .= "</div>\n";
			}
			
			return $html;
		}
		/**
		*
		*/
		function IngresoMedicamento($arreglo)
		{
			IncludeClass('ConexionBD');
      $cxn = new ConexionBD();
      $pt_texto = $this->PlanTerapeuticoActual(SessionGetVar("EvolucionHc"));

      $sql  = "INSERT INTO hc_formulacion_medicamentos_eventos( ";
			$sql .= "				ingreso,";
			$sql .= "				evolucion_id,";
			$sql .= "				codigo_producto,";
			$sql .= "				usuario_id,";
			$sql .= "				fecha_registro,";
			$sql .= "				observacion,"; 	
			$sql .= "				via_administracion_id,";
			$sql .= "				unidad_dosificacion,";
			$sql .= "				dosis,";
			$sql .= "				frecuencia,";
			$sql .= "				cantidad, ";
			$sql .= "				usuario_registro, ";
      $sql .= "       dias_tratamiento ";
			$sql .= "				) ";
			$sql .= "VALUES( ";
			$sql .= "				 ".SessionGetVar("IngresoHc").", ";
			$sql .= "				 ".SessionGetVar("EvolucionHc").", ";
			$sql .= "				'".$arreglo[0]."', ";
			$sql .= "				 ".$arreglo[10].",";
			$sql .= "				 NOW(),";
			$sql .= "				'".$arreglo[5]."',";
			$sql .= "				'".$arreglo[4]."',";
			$sql .= "				'".$arreglo[1]."',";
			$sql .= "				 ".$arreglo[3].",";
			$sql .= "				'".$arreglo[6]."',";
			$sql .= "				 ".$arreglo[2].", ";
			$sql .= "				 ".UserGetUID().", ";
      $sql .= "				 ".(($arreglo[11])? $arreglo[11]:"NULL")." ";      
			$sql .= "				) ";
			
			$cxn->ConexionTransaccion();
      if(!$rst = $cxn->ConexionTransaccion($sql)) return false;
      
      $sql  = "UPDATE solicitudes_tratamiento ";
			$sql .= "SET    sw_finalizado = '1', ";
			$sql .= "       fecha_siguiente_solictud = NULL ";
			$sql .= "WHERE  ingreso = ".SessionGetVar("IngresoHc")." ";
			$sql .= "AND    codigo_medicamento = '".$arreglo[0]."' ";
			$sql .= "AND    sw_finalizado = '0' ";
      
      if(!$rst = $cxn->ConexionBaseDatos($sql)) return false;
      
      $add_dias = 1;
      if($arreglo[13] == "Dia(s)")
        $add_dias = $arreglo[12];
      else if($arreglo[13] == "Semana(s)")
        $add_dias = 7*$arreglo[12];
      
      $fecha_fin = date("Y-m-d", mktime(0, 0, 0,date("m"),(date("d")+$arreglo[11]-1),date("Y")));
      
      $sql  = "INSERT INTO solicitudes_tratamiento ";
			$sql .= "   ( ";
			$sql .= "       solicitud_tratamiento_id, ";
			$sql .= "       ingreso, ";
			$sql .= "       codigo_medicamento, ";
			$sql .= "       dias_tratamiento, ";
      $sql .= "				cantidad, ";
			$sql .= "       intensidad_cantidad, ";
			$sql .= "       intensidad, ";
			$sql .= "       incremento_dias, ";
			$sql .= "       fecha_inicio, ";
			$sql .= "       fecha_siguiente_solictud, ";
			$sql .= "       fecha_finalizacion ";
      $sql .= "		) ";
      $sql .= "VALUES ";
      $sql .= "		( ";
      $sql .= "         DEFAULT, ";
      $sql .= "				  ".SessionGetVar("IngresoHc").", ";
      $sql .= "			   '".$arreglo[0]."', ";
      $sql .= "				  ".(($arreglo[11])? $arreglo[11]:"5000").", ";
      $sql .= "				  ".$arreglo[3].", ";
      $sql .= "				  ".$arreglo[12].", ";
      $sql .= "				 '".$arreglo[13]."', ";
      $sql .= "				  ".$add_dias.", ";
      $sql .= "				  NOW(), ";
      $sql .= "				  NOW(), ";
      $sql .= "				 '".$fecha_fin."' ";
      $sql .= "		); ";
      
 			if(!$rst = $cxn->ConexionTransaccion($sql))
        return false;
      
      $cxn->Commit();
      $this->ConsultaMedicamento($arreglo[0]);
      $this->RegistrarSubmoduloAlterno($this->GetVersion());
      
      $datos = SessionGetVar("MedicamentosFormulados");
      $profesional = SessionGetVar("SolicitudAutorizacion");
      if($profesional == 1 || $profesional == 2)
      {
        $texto  = "MEDICAMENTO REFORMULADO: ".$datos[$arreglo[0]]['producto'];
        $texto .= "	".$arreglo[3]." ".$datos[$arreglo[0]]['unidad_dosificacion']." ".$arreglo[6]." , VIA: ".$datos[$arreglo[0]]['nombre'];
        $texto .= ", DIAS DE TRATAMIENTO: ".$arreglo[11];
        
        if($datos[$arreglo[0]]['observacion'])
          $texto .= " \nOBSERVACIONES: ".$datos[$arreglo[0]]['observacion'];
        
        if($pt_texto != "")
        {
          $sql  = "UPDATE hc_plan_terapeutico "; 
          $sql .= "SET    descripcion = '".$pt_texto." \n".$texto."' "; 
          $sql .= "WHERE  evolucion_id = ".SessionGetVar("EvolucionHc")." ";
        }
        else
        {
          $sql  = "INSERT INTO hc_plan_terapeutico ";
          $sql .= "     (";
          $sql .= "       descripcion, ";
          $sql .= "       evolucion_id";
          $sql .= "     ) ";
          $sql .= "VALUES ";
          $sql .= "     (";
          $sql .= "       '".$texto."', ";
          $sql .= "        ".SessionGetVar("EvolucionHc")."";
          $sql .= "     )";
        }
        
        if(!$rst = $cxn->ConexionBaseDatos($sql)) 
          return false;
        
        $this->RegistrarSubmoduloAlterno($this->GetVersion(),"PlanTerapeuticoTexto");
      }
			return true;
		}
    /**
    *
    */
  	function PlanTerapeuticoActual($evolucion)
  	{
  		$sql  = "SELECT descripcion ";
      $sql .= "FROM   hc_plan_terapeutico ";
      $sql .= "WHERE  evolucion_id = ".$evolucion." ";
      if(!$rst = $this->ConexionBaseDatos($sql))
        return false;
      
      $datos = array();
      if(!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
      
  		return $datos['descripcion'];
  	}
		/********************************************************************************
		*
		*********************************************************************************/
		function ConsultaMedicamento($codigo)
		{
			$sql  = "SELECT ID.descripcion AS producto, ";
			$sql .= "				IA.descripcion AS principio_activo, ";
			$sql .= "				IM.descripcion AS umm, ";
			$sql .= "				HA.nombre, ";
			$sql .= "				FM.dosis, ";
			$sql .= "				FM.unidad_dosificacion, ";
			$sql .= "				FM.cantidad, ";
			$sql .= "				FM.ingreso, ";
			$sql .= "				FM.observacion, ";
			$sql .= "				CASE WHEN FM.sw_estado = '8' THEN '0'";
			$sql .= "						ELSE FM.sw_estado END AS sw_estado, ";
			$sql .= "				FM.codigo_producto, ";
			$sql .= "				FM.via_administracion_id, ";
			$sql .= "				FM.frecuencia, ";
			$sql .= "				IF.descripcion AS forma, ";
			$sql .= "				ME.concentracion_forma_farmacologica AS cff, ";
			$sql .= "				CASE WHEN ME.sw_pos = 1 THEN 'POS'";
			$sql .= "						 ELSE 'NO POS' END AS item, ";
			$sql .= "				SU.nombre AS med_formula, ";
			$sql .= "				SD.nombre AS med_modifica, ";
			$sql .= "				SU.usuario_id, ";
			$sql .= "				FM.sw_confirmacion_formulacion, ";
			$sql .= "				FH.usuario_registro, ";
			$sql .= "				FH.usuario_id, ";
			$sql .= "				FH.dias_tratamiento, ";
			$sql .= "				FM.sw_requiere_autorizacion_no_pos, ";
			$sql .= "				FM.justificacion_no_pos_id ";
			$sql .= "FROM 	inv_med_cod_principios_activos AS IA, ";
			$sql .= "				hc_formulacion_medicamentos FM,";
			$sql .= "				hc_formulacion_medicamentos_eventos FH,";
			$sql .= "				inventarios_productos ID, ";
			$sql .= "				inv_med_cod_forma_farmacologica AS IF, ";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), ";
			$sql .= "				hc_vias_administracion HA, ";
			$sql .= "				system_usuarios SU, ";
			$sql .= "				system_usuarios SD ";
			$sql .= "WHERE	ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		FM.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND		FM.ingreso = ".SessionGetVar("IngresoHc")." ";
			$sql .= "AND		FM.codigo_producto = '".$codigo."' ";
			$sql .= "AND		HA.via_administracion_id = FM.via_administracion_id ";
			$sql .= "AND 		IF.cod_forma_farmacologica = ME.cod_forma_farmacologica ";
			$sql .= "AND		FH.num_reg = FM.num_reg ";
			$sql .= "AND		SU.usuario_id = FH.usuario_id ";
			$sql .= "AND		SD.usuario_id = FH.usuario_registro ";
			$sql .= "ORDER BY FM.sw_estado ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$medica = SessionGetVar("MedicamentosFormulados");
			$medica[$codigo] = $datos;
			$medica[$codigo]['activar'] = "1";
			
			SessionSetVar("MedicamentosFormulados",$medica);
			
			return $datos;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ActualizarMedicamento($arreglo)
		{
			$datos = $this->ConsultaMedicamento($arreglo[0]);
      $pt_texto = $this->PlanTerapeuticoActual(SessionGetVar("EvolucionHc"));

			$datos['sw_estado'] = $arreglo[1];
			
			$sql  = "INSERT INTO hc_formulacion_medicamentos_eventos( ";
			$sql .= "				ingreso,";
			$sql .= "				evolucion_id,";
			$sql .= "				codigo_producto,";
			$sql .= "				usuario_id,";
			$sql .= "				fecha_registro,";
			$sql .= "				observacion,"; 	
			$sql .= "				via_administracion_id,";
			$sql .= "				unidad_dosificacion,";
			$sql .= "				dosis,";
			$sql .= "				cantidad, ";
			$sql .= "				frecuencia, ";
			$sql .= "				sw_estado, ";
			$sql .= "				usuario_registro, ";
      $sql .= "				dias_tratamiento ";
			$sql .= "				) ";
			$sql .= "VALUES( ";
			$sql .= "				 ".SessionGetVar("IngresoHc").", ";
			$sql .= "				 ".SessionGetVar("EvolucionHc").", ";
			$sql .= "				'".$arreglo[0]."', ";
			$sql .= "				 ".$datos['usuario_id'].",";
			$sql .= "				 NOW(),";
			$sql .= "				'".$datos['observacion']."',";
			$sql .= "				'".$datos['via_administracion_id']."',";
			$sql .= "				'".$datos['unidad_dosificacion']."',";
			$sql .= "				 ".$datos['dosis'].",";
			$sql .= "				 ".$datos['cantidad'].", ";
			$sql .= "				'".$datos['frecuencia']."', ";
			$sql .= "				'".$arreglo[1]."',";
			$sql .= "				 ".UserGetUID().", ";
      $sql .= "				 ".(($arreglo[2])? $arreglo[2]:"NULL")." ";
			$sql .= "				) ";
			
      IncludeClass('ConexionBD');
      $cxn = new ConexionBD();
      $cxn->ConexionTransaccion();
      
			if(!$rst = $cxn->ConexionTransaccion($sql))
      {
        echo $cxn->mensajeDeError();
        return false;
			}
      $add_dias = 1;
      $aux = explode(" ",$datos['frecuencia']);
      if($aux[0] == "Cada")
      {
        if($aux[2] == "Dia(s)")
          $add_dias = $aux[1];
        else if($aux[2] == "Semana(s)")
          $add_dias = 7*$aux[1];
      }
      else
      {
        $aux[2] = "Dia(s)";
        $aux[1] = "1";
      }
      
      $fecha_fin = date("Y-m-d", mktime(0, 0, 0,date("m"),(date("d")+$arreglo[2]-1),date("Y")));
      
      $sql  = "INSERT INTO solicitudes_tratamiento ";
			$sql .= "   ( ";
			$sql .= "       solicitud_tratamiento_id, ";
			$sql .= "       ingreso, ";
			$sql .= "       codigo_medicamento, ";
			$sql .= "       dias_tratamiento, ";
      $sql .= "				cantidad, ";
			$sql .= "       intensidad_cantidad, ";
			$sql .= "       intensidad, ";
			$sql .= "       incremento_dias, ";
			$sql .= "       fecha_inicio, ";
			$sql .= "       fecha_siguiente_solictud, ";
			$sql .= "       fecha_finalizacion ";
      $sql .= "		) ";
      $sql .= "VALUES ";
      $sql .= "		( ";
      $sql .= "         DEFAULT, ";
      $sql .= "				  ".SessionGetVar("IngresoHc").", ";
      $sql .= "			   '".$arreglo[0]."', ";
      $sql .= "				   ".(($arreglo[2])? $arreglo[2]:"5000").", ";
      $sql .= "				  ".$datos['cantidad'].", ";
      $sql .= "				  ".$aux[1].", ";
      $sql .= "				 '".$aux[2]."', ";
      $sql .= "				  ".$add_dias.", ";
      $sql .= "				  NOW(), ";
      $sql .= "				  NOW(), ";
      $sql .= "				 '".$fecha_fin."' ";
      $sql .= "		); ";
      
 			if(!$rst = $cxn->ConexionTransaccion($sql))
        return false;
      
      $cxn->Commit();
      $this->RegistrarSubmoduloAlterno($this->GetVersion());

			$datos = $this->ConsultaMedicamento($arreglo[0]);
			$profesional = SessionGetVar("SolicitudAutorizacion");
      if($profesional == 1 || $profesional == 2)
      {
        $texto  = "MEDICAMENTO REFORMULADO: ".$datos['producto'];
        $texto .= "	".$datos['dosis']." ".$datos['unidad_dosificacion']." ".$datos['frecuencia']." , VIA: ".$datos['nombre'];
        $texto .= ", DIAS DE TRATAMIENTO: ".$arreglo[2];
        
        if($datos['observacion'])
          $texto .= " \nOBSERVACIONES: ".$datos['observacion'];
        
        if($pt_texto != "")
        {
          $sql  = "UPDATE hc_plan_terapeutico "; 
          $sql .= "SET    descripcion = '".$pt_texto." \n".$texto."' "; 
          $sql .= "WHERE  evolucion_id = ".SessionGetVar("EvolucionHc")." ";
        }
        else
        {
          $sql  = "INSERT INTO hc_plan_terapeutico ";
          $sql .= "     (";
          $sql .= "       descripcion, ";
          $sql .= "       evolucion_id";
          $sql .= "     ) ";
          $sql .= "VALUES ";
          $sql .= "     (";
          $sql .= "       '".$texto."', ";
          $sql .= "        ".SessionGetVar("EvolucionHc")."";
          $sql .= "     )";
        }
        
        if(!$rst = $cxn->ConexionBaseDatos($sql)) 
          return false;
        
        $this->RegistrarSubmoduloAlterno($this->GetVersion(),"PlanTerapeuticoTexto");
      }
			$html = $this->CrearMedicamentos();
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function ActualizarSolucion($param)
		{		
			$datos = array();
			$solucion = $this->SolucionesFinalizadas($param[0],$param[1]);
			
			foreach($solucion as $key=> $datos);
			
			$sql  = "INSERT INTO hc_formulacion_mezclas_eventos (";
			$sql .= "			num_mezcla,";
      $sql .= "			ingreso ,";
      $sql .= "			evolucion_id ,";
      $sql .= "			usuario_id ,";
      $sql .= "			fecha_registro,";
      $sql .= "			sw_estado ,";
      $sql .= "			observacion,";
      $sql .= "			volumen_infusion,";
      $sql .= "			unidad_volumen,";
      $sql .= "			cantidad ";
			$sql .= ")";
			$sql .= "VALUES(";
			$sql .= "			 ".$param[0].",";
      $sql .= "			 ".SessionGetVar("IngresoHc").",";
      $sql .= "			 ".SessionGetVar("EvolucionHc").",";
      $sql .= "			 ".UserGetUID().",";
      $sql .= "			NOW(),";
      $sql .= "			'".$param[1]."',";
      $sql .= "			'".$datos['observacion']."',";
      $sql .= "			 ".$datos['volumen_infusion'].",";
      $sql .= "			'".$datos['unidad_volumen']."',";
      $sql .= "			 ".$datos['cantidad']." ";
			$sql .= ")";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
      
      $this->RegistrarSubmoduloAlterno($this->GetVersion());
			
			$solucion = $this->SolucionesFinalizadas($param[0],$param[1]);
			$html = $this->CrearMedicamentos();
			return $html;
		}
		/*******************************************************************************
		*
		********************************************************************************/
		function SolucionesFinalizadas($numero,$estado)
		{
			$sql  = "SELECT FM.num_mezcla, ";			
			$sql .= "				FM.volumen_infusion, ";
			$sql .= "				FM.unidad_volumen, ";
			$sql .= "				FM.cantidad, ";
			$sql .= "				FM.observacion, ";
			$sql .= "				CASE WHEN FM.sw_estado = '8' THEN '0'";
			$sql .= "						ELSE FM.sw_estado END AS sw_estado, ";
			$sql .= "				FD.codigo_producto,";
			$sql .= "				FD.sw_solucion, ";
			$sql .= "				FD.cantidad as cmedicamento, ";
			$sql .= "				ID.descripcion AS producto, ";
			$sql .= "				IA.descripcion AS principio_activo, ";
			$sql .= "				IM.descripcion AS umm, ";
			$sql .= "				CASE WHEN ME.sw_pos = 1 THEN 'POS'";
			$sql .= "						 ELSE 'NO POS' END AS item, ";
			$sql .= "				SU.nombre AS med_formula, ";
			$sql .= "				FD.dosis, ";
			$sql .= "				FD.unidad_dosificacion, ";
			$sql .= "				SU.usuario_id ";
			$sql .= "FROM 	hc_formulacion_mezclas FM,";
			$sql .= "				hc_formulacion_mezclas_detalle FD,";
			$sql .= "				inventarios_productos ID, ";
			$sql .= "				inv_med_cod_principios_activos AS IA,";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), ";
			$sql .= "				hc_formulacion_mezclas_eventos FH, ";
			$sql .= "				system_usuarios SU ";
			$sql .= "WHERE	ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		FD.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		FD.num_mezcla = FM.num_mezcla ";
			$sql .= "AND 		FD.num_mezcla = ".$numero." ";
			$sql .= "AND		FM.ingreso = ".SessionGetVar("IngresoHc")." ";
			$sql .= "AND		FH.num_reg = FM.num_reg ";
			$sql .= "AND 		FH.usuario_id = SU.usuario_id ";
			$sql .= "ORDER BY FM.sw_estado,FD.sw_solucion DESC ";
			
 			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$sw_estado = "";
			$soluciones = array();
			$medica = SessionGetVar("SolucionesFormuladas");
			while (!$rst->EOF)
			{
				$soluciones[$rst->fields[6]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			$medica[$numero] = $soluciones;
			$medica[$numero][0]['sw_estado'] = $estado;
			$medica[$numero][0]['activar'] = "1";
			
			print_r($medica);
			
			SessionSetVar("SolucionesFormuladas",$medica);
			
			return $soluciones;
		}
		/**
		*
		*/
		function CrearMedicamentos($arreglo)
		{
			$html = "";
			$path = SessionGetVar("RutaImagenes");
			
			$est0 = "style=\"text-indent:2pt;text-align:left;font-size:10pt;\" ";
			$est1 = "style=\"text-indent:2pt;text-align:left;font-size:7pt;\" ";
			
			$clasesjs .= "new Array('formulacion_table_list_suspendido','formulacion_table_list',";
			$clasesjs .= "'formulacion_table_list_oscuro','formulacion_table_list_claro','label','label2')";
				
			$documentos = SessionGetVar("MedicamentosFormulados");
			
			$cl1 = array(	"formulacion_table_list","modulo_list_claro","formulacion_table_list_oscuro",
										"formulacion_table_list_suspendido","formulacion_table_list_claro",
										"hc_table_submodulo_list_title","modulo_table_list_title","label","label2");
			$cl2 = array(	"formulacion_table_list_suspendido","modulo_list_claro","formulacion_table_list_claro",
										"formulacion_table_list","formulacion_table_list_oscuro","hc_table_submodulo_list_title",
										"formulacion_table_list_suspendido","label2","label");
			$img1 = array ("historia_actual_osc.gif","pactivo.png");
			$img2 = array ("historia_actual_cla.gif","pinactivo.png");
			
			$clases = array("1"=>$cl1,"2"=>$cl2);
			$imagenes = array("1"=>$img1,"2"=>$img2);
			
			$html .= "	<table align=\"center\" border=\"0\" width=\"98%\" class=\"modulo_table_list\">\n";
			$html .= "		<tr class=\"modulo_table_list_title\">\n";
			$html .= "  		<td align=\"center\">PLAN DE MEDICAMENTOS</td>\n";
			$html .= "		</tr>\n";
			$html .= "		<tr>\n";
			$html .= "			<td><br>\n";
			$est0 = "style=\"text-indent:2pt;text-align:left;font-size:11px;\" ";
			$est1 = "style=\"text-indent:2pt;text-align:left;font-size:9px;\" ";
			$estilos = "style=\"border-bottom-width:0px;border-left-width:1px;border-right-width:0px;border-top-width:0px;border-style: solid;\""; 
			
			foreach($documentos as $key=>$datos)
			{
				if($datos['activar'] == "1")
				{
					$clasesjs  = "new Array('".$clases[$datos['sw_estado']][3]."','".$clases[$datos['sw_estado']][0]."'";
					$clasesjs .= ",'".$clases[$datos['sw_estado']][2]."','".$clases[$datos['sw_estado']][4]."',";
					$clasesjs .= "'".$clases[$datos['sw_estado']][7]."','".$clases[$datos['sw_estado']][8]."')";
					
					if($datos['sw_estado'] == '2')
					{
						$clasesjs  = "new Array('".$clases[$datos['sw_estado']][0]."','".$clases[$datos['sw_estado']][3]."',";
						$clasesjs .= "'".$clases[$datos['sw_estado']][4]."','".$clases[$datos['sw_estado']][2]."',";
						$clasesjs .= "'".$clases[$datos['sw_estado']][8]."','".$clases[$datos['sw_estado']][7]."')";
					}
					
					$html .= "<div id=\"CapaFormula".$key."\">\n";
					$html .= "	<table id=\"Bordex".$key."\" align=\"center\" border=\"0\" width=\"100%\" class=\"".$clases[$datos['sw_estado']][2]."\">\n";
					$html .= "		<tr id=\"Formulacion0x".$key."\" class=\"".$clases[$datos['sw_estado']][0]."\">\n";
					$html .= "  		<td width=\"84%\">\n";
					$html .= "				<table id=\"Formulacion1x".$key."\" class=\"".$clases[$datos['sw_estado']][0]."\" >\n";
					$html .= "					<tr >\n";
					$html .= "						<td $est0 >".$datos['producto']."</td>\n";
					$html .= "						<td valign=\"bottom\" id=\"Formulacion2x".$key."\" $est1> (".$datos['principio_activo'].")</td>\n";
					$html .= "					</tr>\n";
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "			<td width=\"4%\" align=\"center\" >\n";
					$html .= "				<a href=\"javascript:EditarFormulacion('".$datos['codigo_producto']."','".$key."',".$datos['sw_estado'].")\"  title=\"EDITAR\">\n";
					$html .= "					<img name =\"Editar\" height=\"18\" src=\"".$path."/images/edita.png\" border=\"0\" >\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "			<td width=\"4%\" align=\"center\">\n";
					$html .= "				<a href=\"javascript:VisualizarHistorial('".$datos['codigo_producto']."')\"  title=\"HISTORIAL\">\n";
					$html .= "					<img name =\"Historial".$key."\" height=\"18\"  src=\"".$path."/images/HistoriaClinica1/".$imagenes[$datos['sw_estado']][0]."\" border=\"0\">\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "			<td width=\"4%\" align=\"center\">\n";
					$html .= "				<a href=\"javascript:DatosActuales('".$key."',$clasesjs,'".$datos['codigo_producto']."',".$datos['sw_estado'].");Iniciar('".$datos['producto']."');\" >\n";					
					$html .= "					<img width=\"16\" height=\"18\" title=\"SUSPENDER MEDICAMENTO\" src=\"".$path."/images/".$imagenes[$datos['sw_estado']][1]."\" border=\"0\" name=\"Suspender".$key."\" >\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "			<td width=\"4%\" align=\"center\" >\n";
					$html .= "				<a href=\"javascript:Finalizar('".$datos['codigo_producto']."','".$key."',".$datos['sw_estado'].",'".$datos['producto']."')\"  title=\"FINALIZAR MEDICAMENTO\">\n";
					$html .= "					<img name =\"Finalizar\" height=\"18\" src=\"".$path."/images/HistoriaClinica1/cerrar_claro.gif\" border=\"0\" >\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					$html .= "		<tr class=\"".$clases[$datos['sw_estado']][1]."\">\n";
					$html .= "			<td colspan=\"5\">\n";
					$html .= "				<table width=\"100%\">\n";
					$html .= "					<tr>\n";
					$html .= "						<td width=\"60%\" valign=\"top\">\n";
					$html .= "							<table id=\"Formulacion3x1".$key."\" class=\"".$clases[$datos['sw_estado']][7]."\" >\n";
					$html .= "								<tr>\n";
					$html .= "									<td >VIA DE ADMINISTRACIÓN: </td>\n";
					$html .= "									<td colspan=\"3\">".$datos['nombre']."</td>\n";
					$html .= "								</tr>\n";
					$html .= "								<tr >\n";
					$html .= "									<td >DOSIS</td>\n";
					$html .= "									<td align=\"right\">".$datos['dosis']."</td><td>".$datos['unidad_dosificacion']."</td>\n";
					$html .= "									<td align=\"left\">".$datos['frecuencia']."</td>\n";
					$html .= "								</tr>\n";				
					$html .= "								<tr >\n";
					$html .= "									<td >CANTIDAD</td>\n";
					$html .= "									<td align=\"right\">".$datos['cantidad']."</td><td colspan=\"2\">".$datos['umm']."</td>\n";
					$html .= "								</tr>\n";
          $html .= "								<tr >\n";
          $html .= "									<td >DIAS TRATAMIENTO</td>\n";
					$html .= "									<td align=\"right\">".intval($datos['dias_tratamiento'])."</td>\n";
          $html .= "                  <td colspan=\"2\"></td>\n";
					$html .= "								</tr>\n";
					$html .= "							</table>\n";
					
					$html .= "						</td>\n";
					$html .= "						<td width=\"40%\" valign=\"top\" $estilos>\n";
					$html .= "							<table align=\"center\" id=\"Formulacion3x2".$key."\" class=\"".$clases[$datos['sw_estado']][7]."\" width=\"98%\">\n";
					$html .= "								<tr>\n";
					$html .= "									<td align=\"left\">FORMULÓ: <font style=\"font-weight:normal;\">".$datos['med_formula']."</font></td>\n";
					$html .= "								</tr>\n";
					if($datos['med_modifica'] != $datos['med_formula'])
					{
						$html .= "								<tr>\n";
						$html .= "									<td align=\"left\">MODIFICO: <font style=\"font-weight:normal;\">".$datos['med_modifica']."</font></td>\n";
						$html .= "								</tr>\n";
					}
					$usuariohc = UserGetUID();
					if($datos['sw_confirmacion_formulacion'] == '0' && $datos['usuario_id'] == $usuariohc)
					{
						$html .= "								<tr>\n";
						$html .= "									<td align=\"center\"><a href=\"\" class=\"normal_10AN\">CONFIRMAR</a></td>\n";
						$html .= "								</tr>\n";
					}
					
					if($datos['sw_requiere_autorizacion_no_pos'] == 'S' && !$datos['justificacion_no_pos_id'])
					{
						$html .= "								<tr>\n";
						$html .= "									<td id=\"justificacion".$datos['codigo_producto']."\" align=\"center\"><a href=\"javascript:Justificar('".$datos['codigo_producto']."','".$datos['justificacion_no_pos_id']."')\" class=\"normal_10AN\">JUSTIFICAR</a></td>\n";
						$html .= "								</tr>\n";					
					}
					else if($datos['sw_requiere_autorizacion_no_pos'] == 'S' && $datos['justificacion_no_pos_id'])
						{
							$html .= "								<tr>\n";
							$html .= "									<td id=\"justificacion".$datos['codigo_producto']."\" align=\"center\"><a href=\"javascript:Justificar('".$datos['codigo_producto']."','".$datos['justificacion_no_pos_id']."')\" class=\"normal_10AN\">VER JUSTIFICACIÓN</a></td>\n";
							$html .= "								</tr>\n";					
						}
						else if($datos['sw_requiere_autorizacion_no_pos'] == 'P' )
							{
								$html .= "								<tr>\n";
								$html .= "									<td id=\"justificacion".$datos['codigo_producto']."\" align=\"center\"><b class=\"normal_10AN\">MEDICAMENTO NO POS A PETICION DEL PACIENTE</B></td>\n";
								$html .= "								</tr>\n";					
							}
							else if($datos['sw_requiere_autorizacion_no_pos'] == 'N' )
							{
								$html .= "								<tr>\n";
								$html .= "									<td id=\"justificacion".$datos['codigo_producto']."\" align=\"center\"><b class=\"normal_10AN\">MEDICAMENTO POS</b></td>\n";
								$html .= "								</tr>\n";					
							}	
					
					$html .= "							</table>\n";
					$html .= "						</td>\n";
					$html .= "					</tr>\n";					
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					
					if($datos['observacion'] != "")
					{
						$html .= "		<tr class=\"".$clases[$datos['sw_estado']][1]."\">\n";
						$html .= "			<td colspan=\"5\">\n";
						$html .= "				<table width=\"100%\" id=\"Formulacion5x".$key."\" class=\"".$clases[$datos['sw_estado']][7]."\">\n";
						$html .= "					<tr>\n";
						$html .= "						<td valign=\"top\" width=\"30%\">\n";
						$html .= "							OBSERVACIONES E INDICACIONES DE SUMINISTRO</td>\n";
						$html .= "						</td>\n";
						$html .= "						<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
						$html .= "							".$datos['observacion']."\n";
						$html .= "						</td>\n";
						$html .= "					</tr>\n";
						$html .= "				</table>\n";
						$html .= "			</td>\n";
						$html .= "		</tr>\n";
					}
					
					$html .= "	</table><br>";
					$html .= "</div>\n";
				}
			}
			
			$soluciones = SessionGetVar("SolucionesFormuladas");
			print_r($soluciones);
			$j = sizeof($soluciones);
			$est0 = "style=\"text-indent:2pt;font-size:11px;\" ";
			$est1 = "style=\"text-indent:2pt;font-size:9px;\" ";
			foreach($soluciones as $key => $nivel1)
			{
				if($nivel1[0]['activar'] == "1")
				{
					$html .= "<div id=\"CapaSolucion".$j."\">\n";
					$html .= "	<table align=\"center\" border=\"0\" width=\"100%\" class=\"".$clases[$nivel1[0]['sw_estado']][2]."\">\n";
					$html .= "		<tr id=\"Solucion1".$j."\" class=\"".$clases[$nivel1[0]['sw_estado']][0]."\">\n";
					$html .= "  		<td width=\"84%\">\n";
					$html .= "				<table id=\"Solucion2".$j."\" class=\"".$clases[$nivel1[0]['sw_estado']][0]."\" >\n";
					$html .= "					<tr >\n";
					$html .= "						<td valign=\"bottom\" $est0 >SOLUCION</td>\n";
					$html .= "					</tr>\n";
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					
					if(SessionGetVar("tipoProfesionalhc") == '1')
					{
						$html .= "			<td width=\"4%\" align=\"center\" >\n";
						$html .= "				<a href=\"javascript:CrearEdicion('CapaSolucion".$j."','".$key."',".$nivel1[0]['sw_estado'].")\"  title=\"EDITAR\">\n";
						$html .= "					<img name =\"Editar\" height=\"18\" src=\"".$path."/images/edita.png\" border=\"0\" >\n";
						$html .= "				</a>\n";
						$html .= "			</td>\n";
					}
					
					$html .= "			<td width=\"4%\" align=\"center\">\n";
					$html .= "				<a href=\"javascript:VerHistorial(new Array('".$key."'))\"  title=\"HISTORIAL\">\n";
					$html .= "					<img name =\"HistorialS".$j."\" height=\"18\"  src=\"".$path."/images/HistoriaClinica1/".$imagenes[$nivel1[0]['sw_estado']][0]."\" border=\"0\">\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "			<td width=\"4%\" align=\"center\">\n";
					$html .= "				<a href=\"javascript:DatosActuales(".$j.",$clasesjs,'".$key."',".$nivel1[0]['sw_estado'].");IniciarS('SOLUCION');\" >\n";
					$html .= "					<img name =\"SuspenderS".$j."\" width=\"16\" height=\"18\" title=\"SUSPENDER SOLUCION\" src=\"".$path."/images/".$imagenes[$nivel1[0]['sw_estado']][1]."\" border=\"0\">\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "			<td width=\"4%\" align=\"center\" >\n";
					$html .= "				<a href=\"javascript:FinalizarS('".$key."','".$j."')\"  title=\"FINALIZAR SOLUCION\">\n";
					$html .= "					<img name =\"Finalizar\" height=\"18\" src=\"".$path."/images/HistoriaClinica1/cerrar_claro.gif\" border=\"0\" >\n";
					$html .= "				</a>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					$html .= "		<tr >\n";
					$html .= "			<td colspan=\"5\">\n";
					$html .= "				<table id=\"Solucion0".$j."\"  class=\"".$clases[$nivel1[0]['sw_estado']][0]."\" width=\"100%\">\n";
					foreach($nivel1 as $key0=> $nivel2)
					{
						if($nivel2['sw_solucion'] == '1') 
						{
							$html .= "					<tr>\n";
							$html .= "						<td valign=\"bottom\" align=\"left\"  $est0 width=\"75%\">".$nivel2['producto']." <font $est1>(".$nivel2['principio_activo'].")</font></td>\n";
							$html .= "						<td valign=\"bottom\" align=\"right\" $est1 width=\"10%\">".$nivel2['dosis']."</td>\n";
							$html .= "						<td valign=\"bottom\" align=\"left\"  $est1 width=\"15%\">".$nivel2['unidad_dosificacion']."</td>\n";
							$html .= "					</tr>\n";
						}
						$key3 = $key0;
					}
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
						
					$html .= "		<tr>\n";
					$html .= "			<td colspan=\"5\" class=\"modulo_list_oscuro\">\n";
					$html .= "				<table id=\"Solucion3".$j."\"  class=\"".$clases[$nivel1[0]['sw_estado']][7]."\" width=\"100%\">\n";
					$key3 = 0;
					foreach($nivel1 as $key1=> $nivel3)
					{
						if($nivel3['sw_solucion'] == '0') 
						{
							$html .= "					<tr>\n";
							$html .= "						<td valign=\"bottom\" align=\"left\"  $est0 width=\"75%\">".$nivel3['producto']." <font $est1>(".$nivel3['principio_activo'].")</font></td>\n";
							$html .= "						<td valign=\"bottom\" align=\"right\" $est1 width=\"10%\">".$nivel3['dosis']."</td>\n";
							$html .= "						<td valign=\"bottom\" align=\"left\"  $est1 width=\"15%\">".$nivel3['unidad_dosificacion']."</td>\n";
							$html .= "					</tr>\n";
							$key3 = $key1;
						}
					}
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n";
					
	 				$html .= "		<tr class=\"".$clases[$nivel1[0]['sw_estado']][1]."\">\n";
					$html .= "			<td colspan=\"5\">\n";
					$html .= "				<table width=\"100%\">\n";
					$html .= "					<tr>\n";
					$html .= "						<td width=\"60%\" valign=\"top\">\n";
					$html .= "							<table id=\"Solucion41".$j."\" class=\"".$clases[$nivel1[0]['sw_estado']][7]."\" >\n";
					$html .= "								<tr>\n";
					$html .= "									<td >CANTIDAD TOTAL </td>\n";
					$html .= "									<td >".$nivel1[$key3]['cantidad']."</td><td colspan=\"2\"><b>Unidad(es)</b></td>\n";
					$html .= "								</tr>\n";
					$html .= "								<tr >\n";
					$html .= "									<td >VOLUMEN DE INFUSIÓN</td>\n";
					$html .= "									<td align=\"right\">".$nivel1[$key3]['volumen_infusion']."</td><td colspan=\"2\">".$nivel1[$key3]['unidad_volumen']."</td>\n";
					$html .= "								</tr>\n";				
					$html .= "							</table>\n";
					
					$html .= "						</td>\n";
					$html .= "						<td width=\"40%\" valign=\"top\" $estilos>\n";
					$html .= "							<table align=\"center\" id=\"Solucion42".$j."\" class=\"".$clases[$nivel1[0]['sw_estado']][7]."\" >\n";
					$html .= "								<tr>\n";
					$html .= "									<td align=\"center\">FORMULÓ:</td>\n";
					$html .= "								</tr>\n";
					$html .= "								<tr>\n";
					$html .= "									<td style=\" font-weight:normal\">".$nivel1[$key3]['med_formula']."</td>\n";
					$html .= "								</tr>\n";
					$html .= "							</table>\n";
					$html .= "						</td>\n";
					$html .= "					</tr>\n";	
					$html .= "				</table>\n";
					$html .= "			</td>\n";
					$html .= "		</tr>\n"; 
				
					if($nivel1[$key3]['observacion'] != "")
					{
						$html .= "		<tr class=\"".$clases[$nivel1[0]['sw_estado']][1]."\" >\n";
						$html .= "			<td colspan=\"5\" width=\"100%\" >\n";
						$html .= "				<table width=\"100%\" id=\"Solucion5".$j."\" class=\"".$clases[$nivel1[0]['sw_estado']][7]."\">\n";
						$html .= "					<tr>\n";
						$html .= "						<td valign=\"top\" width=\"30%\">\n";
						$html .= "							OBSERVACIONES E INDICACIONES DE SUMINISTRO:</td>\n";
						$html .= "						</td>\n";
						$html .= "						<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
						$html .= "							".$nivel1[$key3]['observacion']."\n";
						$html .= "						</td>\n";
						$html .= "					</tr>\n";
						$html .= "				</table>\n";
						$html .= "			</td>\n";
						$html .= "		</tr>\n";
					}
						
					$html .= "	</table><br>";
					$html .= "</div>\n";
					$j++;
				}
			}
			
			$html .= "			</td>\n";
			$html .= "		</tr>\n";
			$html .= "	</table><br>";
				
			return $html;
		}
		/**
		* Funcion que permite realizar la conexion a la base de datos y ejecutar la 
		* consulta sql 
		* 
		* @param string sentencia sql a ejecutar 
		* @return rst 
		*/
		function ConexionBaseDatos($sql)
		{
			list($dbconn)=GetDBConn();
			$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
    /**
    *
    */
    function RegistrarSubmoduloAlterno($DatosVersion=array('version'=>'1','subversion'=>'0'),$submod="PlanTerapeuticoHospitalizacion")
    {
      list($dbconn) = GetDBconn();
      $sql  = "DELETE FROM hc_evoluciones_submodulos ";
      $sql .= "WHERE  evolucion_id = ".SessionGetVar("EvolucionHc")." ";
      $sql .= "AND    submodulo = '".$submod."'; ";
      $sql .= "INSERT INTO hc_evoluciones_submodulos";
      $sql .= "     (";
      $sql .= "       ingreso,";
      $sql .= "       evolucion_id,";
      $sql .= "       submodulo,";
      $sql .= "       version,";
      $sql .= "       subversion ";
      $sql .= "     ) ";
      $sql .= "VALUES";
      $sql .= "     ( ";
      $sql .= "       ".SessionGetVar("IngresoHc").",";
      $sql .= "       ".SessionGetVar("EvolucionHc").",";
      $sql .= "       '".$submod."',";
      $sql .= "       '".$DatosVersion[version]."',";
      $sql .= "       '".$DatosVersion[subversion]."'";
      $sql .= "     ); ";

      $dbconn->Execute($sql);

      if ($dbconn->ErrorNo() != 0)
      {
        $this->error = "Error al Cargar el Modulo";
        $this->mensajeDeError = "Error DB : " . $dbconn->ErrorMsg();
        return false;
      }
      else
      {
        return true;
      }
    }
    /**
		* Esta función retorna los datos de concernientes a la version del submodulo
		* @access private
		*/
		function GetVersion()
		{
			$informacion=array(
			'version'=>'1',
			'subversion'=>'0',
			'revision'=>'0',
			'fecha'=>'01/27/2005',
			'autor'=>'HUGO F. MANRIQUE',
			'descripcion_cambio' => '',
			'requiere_sql' => false,
			'requerimientos_adicionales' => '',
			'version_kernel' => '1.0'
			);
			return $informacion;
		}


	}
	$oRS = new procesos_admin( array( 'ActivarMenu'));
	$oRS->action();	
?>