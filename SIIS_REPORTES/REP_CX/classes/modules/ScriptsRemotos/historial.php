<?php
	/**************************************************************************************
	* $Id: historial.php,v 1.6 2006/11/03 16:01:55 hugo Exp $ 
	* @copyright (C) 2005 IPSOFT - SA (www.ipsoft-sa.com)
	* @package IPSOFT-SIIS
	*
	* @author Hugo F. Manrique	
	**************************************************************************************/
	$VISTA = "HTML";
	$_ROOT = "../../../";
	include  "../../../classes/rs_server/rs_server.class.php";
	include	 "../../../includes/enviroment.inc.php";

	class procesos_admin extends rs_server
	{
		function ConsultarHistorial($param)
		{
			$formulacion = $this->ConsultaMedicamentosFormulados(SessionGetVar("IngresoHc"),$param[0]);
			$formulados = $formulacion['formulacion'];
			if(sizeof($formulados) > 0)
			{
				$modifica = $this->ConsultaAccionesMedicamentos(SessionGetVar("IngresoHc"),$param[0]);
				$datos = array();
				foreach($formulados as $key => $datos)
				{
					$html .= "				<table align=\"center\" width=\"100%\">\n";
					$html .= "					<tr>\n";
					$html .= "						<td align=\"center\" class=\"normal_11N\" colspan=\"4\">".$datos['producto']."<br>(<font class=\"normal_09N\">".$datos['principio_activo']."</font>)<br></td>\n";
					$html .= "					</tr>\n";
					$html .= "					<tr>\n";
					$html .= "						<td class=\"label_mark\">FORMULÓ</td>\n";
					$html .= "						<td class=\"label\" colspan=\"3\">".$datos['usuario']."</td>\n";
					$html .= "					</tr>\n";
					$html .= "					<tr>\n";
					$html .= "						<td class=\"label_mark\">FECHA FORMULACIÓN:</td>\n";
					$html .= "						<td class=\"label\" colspan=\"3\">".$datos['fecha']."</td>\n";
					$html .= "					</tr>\n";
					if(sizeof($modifica[$key]) > 0)
					{
						$html .= "					<tr class=\"normal_10\">\n";
						$html .= "						<td colspan=\"4\">\n";
						$html .= "							<center class=\"label_mark\">HISTORICO FORMULACION</center>\n";
						$html .= "							<table width=\"100%\" class=\"modulo_table_list\">\n";
						$html .= "								<tr class=\"modulo_table_list_title\" >\n";
						$html .= "									<td width=\"25%\">FECHA</td>\n";
						$html .= "									<td width=\"40%\">FORMULÓ</td>\n";
						$html .= "									<td width=\"35%\">ACCION</td>\n";
						$html .= "								</tr>\n";

						foreach($modifica[$key] as $key => $datos1)
						{
							$html .= "								<tr class=\"modulo_list_claro\" >\n";
							$html .= "									<td align=\"center\" >".$datos1['fecha']."</td>\n";
							$html .= "									<td align=\"center\" >".$datos1['usuario']."</td>\n";
							$estado = "";
							if($datos1['sw_estado'] == '1')
								$estado = "ACTIVACIÓN / MODIFACIÓN";
							else if($datos1['sw_estado'] == '2')
									$estado = "SUSPENSIÓN";
								else
									$estado = "FINALIZACIÓN";
								
							$html .= "									<td align=\"center\" class=\"label_mark\">".$estado."</td>\n";
							$html .= "								</tr>\n";
							
							if(	$datos1['sw_observacion'] == '1' || $datos1['sw_via_administracion_id'] == '1' || $datos1['sw_unidad_dosificacion'] == '1'
									|| $datos1['sw_dosis'] == '1' ||	$datos1['sw_frecuencia'] == '1' || $datos1['sw_cantidad'] == '1'
								)
							{
								$html .= "					<tr class=\"modulo_list_claro\" >\n";
								$html .= "						<td align=\"center\" class=\"normal_10AN\">CAMBIOS</td>\n";
								$html .= "						<td colspan=\"2\">\n";
								$html .= "							<table class=\"normal_10\" >\n";
								if($datos1['sw_via_administracion_id'] == '1')
								{
									$html .= "								<tr>\n";
									$html .= "									<td >VIA DE ADMINISTRACIÓN: </td>\n";
									$html .= "									<td colspan=\"3\">".$datos1['nombre']."</td>\n";
									$html .= "								</tr>\n";
								}
								if($datos1['sw_dosis'] == '1' ||	$datos1['sw_frecuencia'] == '1' || $datos1['sw_unidad_dosificacion'] == '1')
								{
									$html .= "								<tr>\n";
									$html .= "									<td >DOSIS</td>\n";
									$html .= "									<td align=\"right\">".$datos1['dosis']."</td><td>".$datos1['unidad_dosificacion']."</td>\n";
									$html .= "									<td align=\"left\">".$datos1['frecuencia']."</td>\n";
									$html .= "								</tr>\n";
								}
								if($datos1['sw_cantidad'] == '1')
								{
									$html .= "								<tr >\n";
									$html .= "									<td >CANTIDAD</td>\n";
									$html .= "									<td align=\"right\">".$datos1['cantidad']."</td><td colspan=\"2\">".$datos['umm']."</td>\n";
									$html .= "								</tr>\n";
								}	
								if($datos1['observacion'] != "" && $datos1['sw_observacion'] == '1' )
								{
									$html .= "								<tr>\n";
									$html .= "									<td valign=\"top\" width=\"30%\">\n";
									$html .= "										OBSERVACIONES E INDICACIONES DE SUMINISTRO</td>\n";
									$html .= "									</td>\n";
									$html .= "									<td valign=\"top\" width=\"70%\" align=\"justify\" colspan=\"4\" >\n";
									$html .= "										".$datos1['observacion']."\n";
									$html .= "									</td>\n";
									$html .= "								</tr>\n";
								}
								$html .= "							</table>\n";
								$html .= "						</td>\n";
								$html .= "					</tr>\n";
							}
						}
						$html .= "							</table><br>\n";
						$html .= "						</td>\n";
						$html .= "					</tr>\n";
					}

					if($datos['usuario_suministro'])
					{
						$html .= "					<tr>\n";
						$html .= "						<td align=\"center\" colspan=\"4\">\n";
						
						$html .= "							<center class=\"label_mark\">REGISTRO DE ADMINISTRACION DE MEDICAMENTOS</center>\n";
						
						$html .= "							<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
						$html .= "								<tr class=\"modulo_table_list_title\" >\n";
						$html .= "									<td align=\"center\" width=\"%\">Fecha</td>\n";
						$html .= "									<td align=\"center\" width=\"%\">Usuario</td>\n";
						$html .= "									<td align=\"center\" width=\"%\">Cantidad</td>\n";
						$html .= "									<td align=\"center\" width=\"%\">Desechos</td>\n";
						$html .= "									<td align=\"center\" width=\"%\">Entregas</td>\n";
						$html .= "									<td align=\"center\" width=\"30%\">Observación</td>\n";
						$html .= "								</tr>\n";
						foreach($formulacion['suministro'][$datos['num_reg']] as $keyII => $suministro)
						{
							$html .= "								<tr class=\"modulo_list_claro\">\n";
							$html .= "									<td valign=\"top\" width=\"%\" align=\"center\" class=\"normal_10AN\">\n";
							$html .= "										".$suministro['fecha_suministro']."\n";
							$html .= "									</td>\n";
							$html .= "									<td valign=\"top\" width=\"27%\" align=\"center\" class=\"label_mark\">\n";
							$html .= "										".$suministro['usuario_suministro']."\n";
							$html .= "									</td>\n";							
							$html .= "									<td valign=\"top\" width=\"%\" class=\"normal_10\" align=\"right\">".$this->SeleccionFactorConversion($datos['codigo_producto'], $datos['unidad_id'],$datos['unidad_dosificacion'],$suministro['cantidad_suministrada'])."</td>\n";
							$html .= "									<td valign=\"top\" width=\"%\" class=\"normal_10\" align=\"right\">".$this->SeleccionFactorConversion($datos['codigo_producto'], $datos['unidad_id'],$datos['unidad_dosificacion'],$suministro['cantidad_perdidas'])."</td>\n";
							$html .= "									<td valign=\"top\" width=\"%\" class=\"normal_10\" align=\"right\">\n";
							$html .= "										".$suministro['cantidad_aprovechada']."\n";
							$html .= "									</td>\n";
							$html .= "									<td valign=\"top\" width=\"25%\" align=\"justify\" >\n";
							$html .= "										".$suministro['observacion_suministro']."&nbsp;\n";
							$html .= "									</td>\n";
							$html .= "								</tr>\n";
						}
						$html .= "							</table><br>\n";
						$html .= "						</td>\n";
						$html .= "					</tr>\n";
					}
					$html .= "				</table><br>\n";
					break;
				}
			}
			return $html;
		}
		/**************************************************************************
		* Consulta el historial de un medicamento junto con el suministro
		* @param int $ingreso Numero de ingreso del paciente
		* @param string $producto Codigo del producto
		*
		* @return array datos del historial agrupados por num_reg y suministro_id
		***************************************************************************/  
    function ConsultaMedicamentosFormulados($ingreso,$producto)
    {
			$sql  = "
						  SELECT 
						    FM.num_reg, 
						    ID.descripcion AS producto, 
						    IA.descripcion AS principio_activo, 
						    IM.descripcion AS umm, 
						    HA.nombre, 
						    FM.dosis,     
						    FM.unidad_dosificacion, 
						    FM.cantidad,  
						    FM.observacion, 
						    FM.sw_estado, 
						    FM.codigo_producto, 
						    FM.frecuencia, 
						    FM.via_administracion_id, 
						    TO_CHAR(FM.fecha_registro,'DD/MM/YYYY HH:MI AM') AS fecha, 
						    CASE WHEN ME.sw_pos = 1 THEN 'POS' ELSE 'NO POS' END AS item,
						    SU.nombre AS usuario, 
						    SE.observacion AS observacion_suministro, 
						    TO_CHAR(SE.fecha_realizado,'DD/MM/YYYY HH24:MI') AS fecha_suministro, 
						    (select US.nombre from system_usuarios US where SE.usuario_id_control = US.usuario_id) as usuario_suministro, 
						    SE.cantidad_suministrada, 
						    SE.cantidad_aprovechada, 
						    SE.cantidad_perdidas, 
						    ID.unidad_id 
						  FROM   
						    inv_med_cod_principios_activos AS IA, 
						    hc_formulacion_medicamentos_eventos FM 
						    LEFT JOIN hc_formulacion_suministro_medicamentos AS SE
						    ON(SE.num_reg_formulacion = FM.num_reg AND SE.sw_estado = '1'), 
						    hc_formulacion_medicamentos_historico FH, 
						    inventarios_productos ID, 
						    medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM 
						    ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), 
						    hc_vias_administracion HA, 
						    system_usuarios SU 
						  WHERE  
						    ID.codigo_producto = ME.codigo_medicamento 
						    AND ME.cod_principio_activo = IA.cod_principio_activo 
						    AND FM.codigo_producto = ME.codigo_medicamento 
						    AND FM.codigo_producto = '".$producto."' 
						    AND FH.num_reg = FM.num_reg 
						    AND FM.ingreso = ".$ingreso." 
						    AND HA.via_administracion_id = FM.via_administracion_id 
						    AND SU.usuario_id = FM.usuario_id 
						  ORDER BY 
						    FM.num_reg 
				";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$medica = array();
			while (!$rst->EOF)
			{
				$medica['formulacion'][$rst->fields[0]] = $rst->GetRowAssoc($ToUpper = false);
				$medica['suministro'][$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			return $medica;
		}
		/********************************************************************
		*
		*********************************************************************/  
    function ConsultaAccionesMedicamentos($ingreso,$producto)
    {
			$sql  = "SELECT FH.num_reg, ";
			$sql .= "				ID.descripcion AS producto, ";
			$sql .= "				IA.descripcion AS principio_activo, ";
			$sql .= "				IM.descripcion AS umm, ";
			$sql .= "				HA.nombre, ";
			$sql .= "				FM.dosis, ";
			$sql .= "				FM.unidad_dosificacion, ";
			$sql .= "				FM.cantidad, ";
			$sql .= "				FM.observacion, ";			
			$sql .= "				FM.codigo_producto, ";
			$sql .= "				FM.frecuencia, ";
			$sql .= "				FM.via_administracion_id, ";
			$sql .= "				CASE WHEN FM.sw_estado = '8' THEN '0' ";
			$sql .= "						ELSE FM.sw_estado END AS sw_estado, ";	
			$sql .= "				TO_CHAR(FM.fecha_registro,'DD/MM/YYYY HH:MI AM') AS fecha, ";
			$sql .= "				CASE WHEN ME.sw_pos = 1 THEN 'POS'";
			$sql .= "						 ELSE 'NO POS' END AS item,";
			$sql .= "				SU.nombre AS usuario, ";
			$sql .= "				FH.sw_observacion, ";
			$sql .= "				FH.sw_via_administracion_id, ";
			$sql .= "				FH.sw_unidad_dosificacion, ";
			$sql .= "				FH.sw_dosis, ";
			$sql .= "				FH.sw_frecuencia, ";		
			$sql .= "				FH.sw_cantidad ";
			$sql .= "FROM 	inv_med_cod_principios_activos AS IA, ";
			$sql .= "				hc_formulacion_medicamentos_eventos FM, ";
			$sql .= "				hc_formulacion_medicamentos_historico_d FH, ";
			$sql .= "				inventarios_productos ID, ";
			$sql .= "				medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM ";
			$sql .= "				ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), ";
			$sql .= "				hc_vias_administracion HA, ";
			$sql .= "				system_usuarios SU ";
			$sql .= "WHERE	ID.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		ME.cod_principio_activo = IA.cod_principio_activo ";
			$sql .= "AND 		FM.codigo_producto = ME.codigo_medicamento ";
			$sql .= "AND 		FM.codigo_producto = '".$producto."' ";
			$sql .= "AND		FH.num_reg_evento = FM.num_reg ";
			$sql .= "AND		FM.ingreso = ".$ingreso." ";
			$sql .= "AND		HA.via_administracion_id = FM.via_administracion_id ";
			$sql .= "AND		SU.usuario_id = FM.usuario_registro ";
			$sql .= "ORDER BY FH.num_reg_evento ";
			
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$medica = array();
			while (!$rst->EOF)
			{
				$medica[$rst->fields[0]][] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			//print_r($medica);
			return $medica;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function VerHistorialSolucion($param)
		{
 			$historial = $this->HistorialSolucion(SessionGetVar("IngresoHc"),$param[0]);
			print_r($historial);
			
			$html .= "<table align=\"center\" width=\"95%\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\">\n";
			$html .= "	<tr>\n";
			$html .= "		<td class=\"normal_11N\" colspan=\"2\" align=\"center\" height=\"30\">HISTORIAL DE FORMULACIÓN DE LA SOLUCION</td>\n";
			$html .= "	</tr>\n";

			$flag = true;
			$flag1 = true;
			foreach($historial as $key=> $nivel1)
			{
				foreach($nivel1 As $key2=> $nivel2)
				if($flag)
				{
					$html .= "	<tr>\n";
					$html .= "		<td class=\"label\">FORMULÓ</td>\n";
					$html .= "		<td class=\"label_mark\">". $nivel2['nombre']."</td>\n";
					$html .= "	</tr>\n";
					$html .= "	<tr>\n";
					$html .= "		<td class=\"label\">FECHA FORMULACIÓN</td>\n";
					$html .= "		<td class=\"label_mark\">". $nivel2['fecha']."</td>\n";
					$html .= "	</tr>\n";
					$html .= "	<tr>\n";
					$html .= "		<td valign=\"top\" class=\"label\">ESTADO</td>\n";
					$html .= "		<td valign=\"top\" align=\"justify\" class=\"label_mark\">\n";
					$html .= "			". $nivel2['sw_estado']."\n";
					$html .= "		</td>\n";
					$html .= "	</tr>\n";
					$flag = false;
				}
				
				if( $nivel2['usuario_suministro'])
				{
					if($flag1)
					{
						$html .= "	<tr>\n";
						$html .= "		<td valign=\"top\" class=\"label\">SUMISTRÓ</td>\n";
						$html .= "		<td valign=\"top\" align=\"justify\" class=\"label_mark\">\n";
						$html .= "			". $nivel2['usuario_suministro']."\n";
						$html .= "		</td>\n";
						$html .= "	</tr>\n";
						$html .= "	<tr>\n";
						$html .= "		<td valign=\"top\" class=\"label\">FECHA SUMISTRO</td>\n";
						$html .= "		<td valign=\"top\" align=\"justify\" class=\"label_mark\">".$nivel2['fecha_suministro']."</td>\n";
						$html .= "	</tr>\n";

						if( $nivel2['observacion_suministro'] != " ")
						{							
							$html .= "	<tr>\n";
							$html .= "		<td valign=\"top\" colspan=\"2\" class=\"label\" align=\"center\">\n";
							$html .= "			OBSERVACIONES DE SUMINISTRO\n";
							$html .= "		</td>\n";
							$html .= "	</tr>\n";
							$html .= "	<tr>\n";
							$html .= "		<td valign=\"top\" class=\"label_mark\" colspan=\"2\" align=\"justify\" colspan=\"3\" >\n";
							$html .= "			". $nivel2['observacion_suministro']."&nbsp;\n";
							$html .= "		</td>\n";
							$html .= "	</tr>\n";
						}

						$html .= "	<tr class=\"label\">\n";
						$html .= "		<td colspan=\"4\">\n";
						$html .= "			<table width=\"100%\" border=\"1\" rules=\"all\" cellspacing=\"0\" cellpading=\"0\">\n";
						$html .= "				<tr class=\"label\">\n";
						$html .= "					<td align=\"center\">PRODUCTO</td>\n";
						$html .= "					<td align=\"center\" width=\"30%\">CANTIADAD SUMINISTRADA</td>\n";
						$html .= "				</tr>\n";
						$flag1 = false;
					}
					foreach($nivel1 as $keyy => $nively)
					{
						$html .= "					<tr class=\"label\">\n";
						$html .= "						<td >".$nively['producto']."</td>\n";
						$html .= "						<td align=\"right\">".$this->SeleccionFactorConversion($nively['codigo_producto'], $nively['unidad_id'],$nively['unidad_dosificacion'],$nively['cantidad_suministrada'])."</td>\n";
						$html .= "					</tr>\n";
					}
				}
			}
			if(!$flag1)
			{
				$html .= "		</table>\n";
				$html .= "	</td>\n";
				$html .= "</tr>\n";
			}
			$html .= "</table>\n";
			
			return $html;
		}
		/********************************************************************************
		*
		*********************************************************************************/
		function HistorialSolucion($ingreso,$num_mezcla)
		{
			$sql  = "
						SELECT 
						  FM.num_mezcla, 
						  FD.codigo_producto,
						  COALESCE(SE.suministro_id,0) AS sumistro_id, 
						  FM.volumen_infusion, 
						  FM.unidad_volumen, 
						  FM.cantidad, 
						  FM.observacion, 
						  CASE 
						    WHEN FM.sw_estado = '8' OR FM.sw_estado = '0' THEN 'FINALIZADA'
						    WHEN FM.sw_estado = '1' THEN 'ACTIVA'
						    WHEN FM.sw_estado = '2' THEN 'SUSPENDIDA'
						  END AS sw_estado,
						  TO_CHAR(FH.fecha_registro,'DD/MM/YYYY HH:MI AM') AS fecha, 
						  FD.sw_solucion, 
						  FD.cantidad as cmedicamento, 
						  ID.descripcion AS producto, 
						  ID.unidad_id, 
						  IA.descripcion AS principio_activo, 
						  IM.descripcion AS umm, 
						  SU.nombre, 
						  FD.dosis,     
						  FD.unidad_dosificacion, 
						  SE.observacion AS observacion_suministro, 
						  TO_CHAR(SE.fecha_realizado,'DD/MM/YYYY HH24:MI') AS fecha_suministro, 
						  (select US.nombre from system_usuarios US where SE.usuario_id_control = US.usuario_id) as usuario_suministro, 
						  SE.cantidad_suministrada, 
						  SE.cantidad_aprovechada, 
						  SE.cantidad_perdidas 
						FROM   
						  hc_formulacion_mezclas_eventos FH,    
						  hc_formulacion_mezclas FM,
						  hc_formulacion_mezclas_detalle FD 
						  LEFT JOIN  hc_formulacion_suministro_soluciones AS SE
						  ON (SE.num_mezcla = FD.num_mezcla AND SE.codigo_producto = FD.codigo_producto AND SE.sw_estado = '1'), 
						  inventarios_productos ID, 
						  inv_med_cod_principios_activos AS IA,
						  medicamentos ME LEFT JOIN inv_unidades_medida_medicamentos IM 
						  ON(ME.unidad_medida_medicamento_id = IM.unidad_medida_medicamento_id), 
						  system_usuarios SU 
						WHERE  
						  ID.codigo_producto = ME.codigo_medicamento 
						  AND ME.cod_principio_activo = IA.cod_principio_activo 
						  AND FD.codigo_producto = ME.codigo_medicamento 
						  AND FD.num_mezcla = FM.num_mezcla 
						  AND FM.ingreso = ".$ingreso." 
						  AND FD.num_mezcla = FH.num_mezcla 
						  AND FH.ingreso = ".$ingreso." 
						  AND FM.num_mezcla = ".$num_mezcla." 
						  AND FH.usuario_id = SU.usuario_id 
						ORDER BY 
						  FM.num_mezcla,
						  sw_estado,
						  FD.sw_solucion DESC 
				";
		
			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos[$rst->fields[1]][$rst->fields[2]] = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			
			return $datos;
		}
		/********************************************************************************
    * SeleccionFactorConversion
    *
    * Funcion que selecciona el factor de conversion de un medicamento
    * para su suministro en una unidad diferente
		*********************************************************************************/
    function SeleccionFactorConversion($codigo, $unidad, $unidad_dosificacion, $cantidad)
    {        
      $sql = "SELECT	factor_conversion 
							FROM		hc_formulacion_factor_conversion
							WHERE 	codigo_producto = '".$codigo."'
							AND 		unidad_id = '".trim($unidad)."'
							AND			unidad_dosificacion = '".trim($unidad_dosificacion)."';";

 			if(!$rst = $this->ConexionBaseDatos($sql)) return false;
			
			$datos = array();
			while (!$rst->EOF)
			{
				$datos = $rst->GetRowAssoc($ToUpper = false);
				$rst->MoveNext();
			}
			$rst->Close();
			
			$cadenita = $cantidad." ".$unidad_dosificacion;
			if($datos['factor_conversion'])
				$cadenita = ($cantidad*$datos['factor_conversion'])." ".$unidad_dosificacion;
      
			return $cadenita;
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
			$dbconn->debug=true;
			$rst = $dbconn->Execute($sql);
				
			if ($dbconn->ErrorNo() != 0)
			{
				$this->frmError['MensajeError'] = "ERROR DB : " . $dbconn->ErrorMsg();
				return false;
			}
			return $rst;
		}
	}
	$oRS = new procesos_admin( array( 'ActivarMenu', 'CrearTabla'));
	$oRS->action();	
?>
