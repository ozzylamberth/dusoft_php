<?php
	
	class GuiaMantenimiento_HTML
	{
		function GuiaMantenimiento_HTML()
		{			
			
			return true;
		}

		function frmHistoria()
		{
			
			return $this->salida;
		}
		
		function frmConsulta()
		{				
			return $this->salida;
		}
		
		function frmForma($accion,$actividades,$etapas,$parametrizacion,$parametrizacionHC,$evolucion,$edad,$pfj,$resultados)
		{	
			
			$meses=($edad['anos']*12) + $edad['meses'];	
			$this->salida  = ThemeAbrirTablaSubModulo('PARAMETRIZACION DE GUIA DE MANTENIMIENTO DE LA SALUD');
			$this->salida .= "<form name=\"forma$pfj\" action=\"".$accion."\" method=\"post\">";
			$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida .= $this->SetStyle("MensajeError");
			$this->salida .= "      </table>";
			$this->salida .= "	<script language=\"javascript\">\n";
			$this->salida .= "	function AbrirVentana(url)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		window.open(url,'transcribir',\"width=700,height=0,x=2,y=2,resizable=no,status=no,scrollbars=yes,location=no\"); \n";
			$this->salida .= "	}\n";
			$this->salida .= "	function AbrirVentanaVer(url)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		window.open(url,'ver',\"width=710,height=0,x=2,y=2,resizable=no,status=no,scrollbars=yes,location=no\"); \n";
			$this->salida .= "	}\n";			
			$this->salida .= "	</script>";
			$this->salida .= "<table border=\"0\" width=\"100%\" align=\"center\">\n";
			$this->salida .= "<tr><td>\n";			
			$this->salida .= "		  <div class=\"tab-pane\" id=\"ETP\">\n";
			$this->salida .= "			  <script>tabPane = new WebFXTabPane( document.getElementById( \"ETP\" ),false ); </script>\n";			
			foreach($etapas as $etapaId=>$arrE)
			{		
				$this->salida .= "					<div class=\"tab-page\" id=\"etapa$etapaId\">\n";
				$this->salida .= "					<h2 class=\"tab\">".$arrE[descripcion]."</h2>\n";
				$this->salida .= "					<script>	tabPane.addTabPage( document.getElementById(\"etapa$etapaId\")); </script>\n";
				$this->salida .= "					<table border=\"0\" width=\"98%\" align=\"center\" class=\"formulacion_table_list\">\n";
				$this->salida .= "    				<tr class=\"formulacion_table_list\">\n";
				$this->salida .= "        			<td width='10%'  align=\"center\">\n";
				$this->salida .= "          		&nbsp";
				$this->salida .= "          		</td>\n";
				$this->salida .= "        			<td width='25%'  align=\"center\">\n";
				$this->salida .= "          		ACTIVIDAD";
				$this->salida .= "          		</td>\n";
				for($i=$arrE[edadinicio];$i<=$arrE[edadfin];$i++)
				{	
					$this->salida .= "        		<td width='5%'  align=\"center\">\n";
					$this->salida .= "          	$i";
					$this->salida .= "          	</td>\n";
				}		
				$this->salida .= "        		</tr>\n";
				foreach($actividades as $tipoActividad=>$arr)
				{	
					$this->salida .= "					<tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
					$this->salida .= "						<td class=\"normal_10AN\" align=\"center\" rowspan=\"".(count($arr)+1)."\">\n";
					if($tipoActividad=='ED')
					{
						$tipoDes='Educacion';
					}elseif($tipoActividad=='EX')
					{
						$tipoDes='Examen Fisico';
					}else
					{
						$tipoDes='Pr. Tamiz';
					}					
					$this->salida .= "        $tipoDes";
					$this->salida .= "        		</td>\n";				
					foreach($arr as $idActividad => $arr1)
					{
						$this->salida .= "				<tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
						$this->salida .= "					<td class=\"normal_10AN=\" align=\"center\">\n";
						$this->salida .= "					".$arr1[descripcion]."\n";
						$this->salida .= "        	</td>\n";
						for($j=$arrE[edadinicio];$j<=$arrE[edadfin];$j++)
						{	
							$this->salida .= "        		<td width='5%'  align=\"center\" id=\"trans$etapaId$idActividad$j\">\n";							
							if($parametrizacion[$etapaId][$idActividad][$j])
							{
								if($meses<=24)
								{
									if($meses==$j)
									{
										if($parametrizacionHC[$etapaId][$idActividad][$j])
										{
											if($tipoActividad=='ED' || $tipoActividad=='EX')
											{
												$this->salida .= "     <img src=\"".GetThemePath()."/images/asignacion_citas.png\">";
											}
											else
											{
												if($resultados[$etapaId][$idActividad][$j])
												{
													$sw_modo=$resultados[$etapaId][$idActividad][$j]['sw_modo_resultado'];
													$resultado_id=$resultados[$etapaId][$idActividad][$j]['resultado_id'];																											
													$datos="resultado_id=".$resultado_id."&sw_modo=".$sw_modo;
													$url="classes/Visualizar/Visualizar.class.php?".$datos;													
													$this->salida .= "	<a href=\"javascript:AbrirVentanaVer('$url')\"><img src=\"".GetThemePath()."/images/asignacion_citas.png\"></a>";
												}
												else
												{												
													$estilo='modulo_list_claro';
													$datos="cargo=".$arr1['cargo']."&descripcion=".$arr1[descripcion]."&op=Transcribir&periodo=".($r+1)."&estilo=$estilo&evolucion_id=".$evolucion."&trans=trans$etapaId$idActividad$j";
													$url="classes/Transcripcion/TranscripcionExamenes.class.php?".$datos;												
													$this->salida .= "     <a href=\"javascript:AbrirVentana('$url')\"><label class=\"titulo2\">T</label></a>";												
												}	
											}	
										}
										else
										{
											$this->salida .= "          	<input type=\"checkbox\" name=\"".$pfj."EtapaValor".$etapaId."[]\" value=\"".$idActividad.','.$j.','.$arr1['cargo']."\">";										
										}
									}
									elseif($j<$meses)
									{
										if($parametrizacionHC[$etapaId][$idActividad][$j])
										{
											if($tipoActividad=='ED' || $tipoActividad=='EX')
											{
												$this->salida .= "     <img src=\"".GetThemePath()."/images/asignacion_citas.png\">";																							
											}
											else
											{
												if($resultados[$etapaId][$idActividad][$j])
												{
													$sw_modo=$resultados[$etapaId][$idActividad][$j]['sw_modo_resultado'];
													$resultado_id=$resultados[$etapaId][$idActividad][$j]['resultado_id'];																											
													$datos="resultado_id=".$resultado_id."&sw_modo=".$sw_modo;
													$url="classes/Visualizar/Visualizar.class.php?".$datos;													
													$this->salida .= "	<a href=\"javascript:AbrirVentanaVer('$url')\"><img src=\"".GetThemePath()."/images/asignacion_citas.png\"></a>";
												}
												else
												{
													$estilo='modulo_list_claro';
													$datos="cargo=".$arr1['cargo']."&descripcion=".$arr1[descripcion]."&op=Transcribir&periodo=".($r+1)."&estilo=$estilo&evolucion_id=".$evolucion."&trans=trans$etapaId$idActividad$j";
													$url="classes/Transcripcion/TranscripcionExamenes.class.php?".$datos;												
													$this->salida .= "     <a href=\"javascript:AbrirVentana('$url')\"><label class=\"label_error1\">T</label></a>";																																				
												}
											}	
										}
										else
										{											
											$this->salida .= "     <img src=\"".GetThemePath()."/images/alarma.gif\">";											
										}
									}
								}
								else
								{
									if($edad['anos']==$j && $etapaId!=0 && $etapaId!=1)
									{
										if($parametrizacionHC[$etapaId][$idActividad][$j])
										{
											if($tipoActividad=='ED' || $tipoActividad=='EX')
											{
												$this->salida .= "     <img src=\"".GetThemePath()."/images/asignacion_citas.png\">";
											}
											else
											{																								
												if($resultados[$etapaId][$idActividad][$j]){
													$sw_modo=$resultados[$etapaId][$idActividad][$j]['sw_modo_resultado'];
													$resultado_id=$resultados[$etapaId][$idActividad][$j]['resultado_id'];																											
													$datos="resultado_id=".$resultado_id."&sw_modo=".$sw_modo;
													$url="classes/Visualizar/Visualizar.class.php?".$datos;													
													$this->salida .= "	<a href=\"javascript:AbrirVentanaVer('$url')\"><img src=\"".GetThemePath()."/images/asignacion_citas.png\"></a>";
												}
												else
												{
													$estilo='modulo_list_claro';
													$datos="cargo=".$arr1['cargo']."&descripcion=".$arr1[descripcion]."&op=Transcribir&periodo=".($r+1)."&estilo=$estilo&evolucion_id=".$evolucion."&trans=trans$etapaId$idActividad$j";
													$url="classes/Transcripcion/TranscripcionExamenes.class.php?".$datos;												
													$this->salida .= "     <a href=\"javascript:AbrirVentana('$url')\"><label class=\"titulo2\">T</label></a>";												
												}	
											}	
										}
										else
										{
											$this->salida .= "          	<input type=\"checkbox\" name=\"".$pfj."EtapaValor".$etapaId."[]\" value=\"".$idActividad.','.$j.','.$arr1['cargo']."\">";										
										}
									}
									elseif(($j<$edad['anos'] && $etapaId!=0 && $etapaId!=1) || ($etapaId===0 || $etapaId===1))
									{
										if($parametrizacionHC[$etapaId][$idActividad][$j])
										{
											if($tipoActividad=='ED' || $tipoActividad=='EX')
											{
												$this->salida .= "     <img src=\"".GetThemePath()."/images/asignacion_citas.png\">";											
											}
											else
											{
												if($resultados[$etapaId][$idActividad][$j])
												{
													$sw_modo=$resultados[$etapaId][$idActividad][$j]['sw_modo_resultado'];
													$resultado_id=$resultados[$etapaId][$idActividad][$j]['resultado_id'];																											
													$datos="resultado_id=".$resultado_id."&sw_modo=".$sw_modo;
													$url="classes/Visualizar/Visualizar.class.php?".$datos;													
													$this->salida .= "	<a href=\"javascript:AbrirVentanaVer('$url')\"><img src=\"".GetThemePath()."/images/asignacion_citas.png\"></a>";
												}
												else
												{
													$estilo='modulo_list_claro';
													$datos="cargo=".$arr1['cargo']."&descripcion=".$arr1[descripcion]."&op=Transcribir&periodo=".($r+1)."&estilo=$estilo&evolucion_id=".$evolucion."&trans=trans$etapaId$idActividad$j";
													$url="classes/Transcripcion/TranscripcionExamenes.class.php?".$datos;												
													$this->salida .= "     <a href=\"javascript:AbrirVentana('$url')\"><label class=\"label_error1\">T</label></a>";																																				
												}
											}	
										}
										else
										{											
											$this->salida .= "     <img src=\"".GetThemePath()."/images/alarma.gif\">";											
										}
									}
								}							
							}												
							$this->salida .= "          	</td>\n";
						}				
						$this->salida .= "      	</tr>\n";				
					}
					$this->salida .= "      		</tr>\n";
				}			
				$this->salida .= "					</table>\n";
				$this->salida .= "					</div>\n";
			}	
			$this->salida .= "			</div>\n";
			$this->salida .= "</td></tr>\n";
			$this->salida .= "<tr>\n";
			$this->salida .= "			<td align=\"center\">\n";
			$this->salida .= "				<input class=\"input-submit\" type=\"submit\" name=\"guardar$pfj\" value=\"Guardar\">";
			$this->salida .= "			</td>";			
			$this->salida .= "</tr>\n";
			$this->salida .= "</table>\n";
			$this->salida .= "</form>";		
			$this->salida .=ThemeCerrarTablaSubModulo();
			
			return $this->salida;
		}
		
		function SetStyle($campo)
		{
			if ($this->frmError[$campo]||$campo=="MensajeError")
			{
				if ($campo=="MensajeError")
				{
					return ("<tr><td align=\"center\" class=\"hc_tderror\" colspan=\"3\">".$this->frmError["MensajeError"]."</td></tr>");
				}
				return ("hc_tderror");
			}
			return ("hc_tdlabel");
		}
		
		
}
?> 
