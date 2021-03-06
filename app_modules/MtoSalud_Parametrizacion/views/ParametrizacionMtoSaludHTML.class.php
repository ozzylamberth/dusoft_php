<?php
	
	class ParametrizacionMtoSaludHTML
	{
		/**
		* Constructor de la clase
		*/
		function ParametrizacionMtoSaludHTML(){}
		/**
		* Crea la forma para la parametrizacion de guia de mantenimiento de la Salud
    *
		* @param array $action Vector de links de la aplicaion
		* @param array 
		*
		* @return String
		*/
		function FormaParametrizarMtoSaludHTML($action,$actividades,$etapas,$parametrizacion)
		{			
			
			$html  = ThemeAbrirTabla('PARAMETRIZACION DE GUIA DE MANTENIMIENTO DE LA SALUD');
			$html .= "<form name=\"formg\" action=\"".$action['guardarParametrizacion']."\" method=\"post\">";
			$html .= "<table border=\"0\" width=\"100%\" align=\"center\">\n";
			$html .= "<tr><td>\n";			
			$html .= "		  <div class=\"tab-pane\" id=\"ETP\">\n";
			$html .= "			  <script>tabPane = new WebFXTabPane( document.getElementById( \"ETP\" ),false ); </script>\n";			
			foreach($etapas as $etapaId=>$arrE)
			{	
				$html .= "					<div class=\"tab-page\" id=\"etapa$etapaId\">\n";
				$html .= "					<h2 class=\"tab\">".$arrE[descripcion]."</h2>\n";
				$html .= "					<script>	tabPane.addTabPage( document.getElementById(\"etapa$etapaId\")); </script>\n";
				$html .= "					<table border=\"0\" width=\"98%\" align=\"center\" class=\"formulacion_table_list\">\n";
				$html .= "    				<tr class=\"formulacion_table_list\">\n";
				$html .= "        			<td width='10%'  align=\"center\">\n";
				$html .= "          		&nbsp";
				$html .= "          		</td>\n";
				$html .= "        			<td width='25%'  align=\"center\">\n";
				$html .= "          		ACTIVIDAD";
				$html .= "          		</td>\n";
				for($i=$arrE[edadinicio];$i<=$arrE[edadfin];$i++)
				{	
					$html .= "        		<td width='5%'  align=\"center\">\n";
					$html .= "          	$i";
					$html .= "          	</td>\n";
				}
				$html .= "        		</tr>\n";
				foreach($actividades as $tipoActividad=>$arr)
				{	
					$html .= "					<tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
					$html .= "						<td class=\"normal_10AN\" align=\"center\" rowspan=\"".(count($arr)+1)."\">\n";
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
					$html .= "        $tipoDes";
					$html .= "        		</td>\n";				
					foreach($arr as $idActividad => $arr1)
					{
						$html .= "				<tr class=\"modulo_list_claro\" onMouseOver=\"mOvr(this,'#ffffff');\" onMouseOut=\"mOut(this,'#DDDDDD');\">\n";
						$html .= "					<td class=\"normal_10AN=\" align=\"center\">\n";
						$html .= "					".$arr1[descripcion]."\n";
						$html .= "        	</td>\n";
						for($j=$arrE[edadinicio];$j<=$arrE[edadfin];$j++)
						{	
							$checked='';
							if($parametrizacion[$etapaId][$idActividad][$j])
							{
								$checked='checked';
							}
							$html .= "        		<td width='5%'  align=\"center\">\n";
							$html .= "          	<input type=\"checkbox\" name=\"EtapaValor".$etapaId."[]\" value=\"".$idActividad.','.$j."\" $checked>";
							$html .= "          	</td>\n";
						}				
						$html .= "      	</tr>\n";				
					}
					$html .= "      		</tr>\n";
				}			
				$html .= "					</table>\n";
				$html .= "					</div>\n";
			}	
			$html .= "			</div>\n";
			$html .= "</td></tr>\n";
			$html .= "<tr>\n";
			$html .= "			<td align=\"center\">\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"Guardar\">";
			$html .= "			</td>";			
			$html .= "</tr>\n";
			$html .= "</table>\n";
			$html .= "</form>";					
			
			$html .= "		<table border=\"-1\" width=\"50%\" align=\"center\" >\n";
			$html .= "		<tr>";
			$html .= "			<form name=\"formn\" action=\"".$action['nuevaActividad']."\" method=\"post\">";
			$html .= "			<td align=\"center\"><br>\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"nuevo\" value=\"Agregar Fila\">";
			$html .= "			</td>";
			$html .= "			</form>";
			$html .= "			<form name=\"formv\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "			<td align=\"center\"><br>\n";
			$html .= "				<input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$html .= "			</td>";
			$html .= "			</form>";
			$html .= "		</tr>";
			$html .= "	</table>";
			$html .= ThemeCerrarTabla();
			return $html;
		}
		
		function FormaAgregarActividadMtoSaludHTML($action)
		{
			$html  = ThemeAbrirTabla('AGREGAR ACTIVIDAD/CARGO - GUIA DE MANTENIMIENTO DE LA SALUD');
			$html .= "<table border=\"0\" width=\"90%\" align=\"center\">\n";
			$html .= "<tr><td>\n";
			$html .= "		  <div class=\"tab-pane\" id=\"APD\">\n";
			$html .= "			  <script>tabPane = new WebFXTabPane( document.getElementById( \"APD\" ) ); </script>\n";			
			$html .= "					<div class=\"tab-page\" id=\"actividad\">\n";
			$html .= "					<h2 class=\"tab\">ADICION DE ACTIVIDAD</h2>\n";
			$html .= "					<script>	tabPane.addTabPage( document.getElementById(\"actividad\")); </script>\n";
			$html .= "          	<form name=\"add_actividad\" id=\"add_actividad\" action=\"".$action['guardarActividad']."\" method=\"post\">\n";			
			$html .= "						<center>\n";
			$html .= "						<BR><fieldset style=\"width:60%\" class=\"fieldset\"><legend class=\"label\">NUEVA FILA</legend>\n";
			$html .= "    				<table border=\"0\" width=\"90%\" align=\"center\">\n";
			$html .= "        		<tr>\n";
			$html .= "        			<td class=\"normal_10AN\" style=\"width:20%\">ACTIVIDAD</td>\n";
			$html .= "          		<td>\n";
			$html .= "          			<input type=\"text\" class=\"input-text\" name=\"actividad\" style=\"width:80%\" maxlength=\"64\" value=\"".$request['actividad']."\">\n";
			$html .= "          		</td>\n";
			$html .= "        		</tr>\n";
			$html .= "        		<tr>\n";
			$html .= "        			<td align=\"center\" class=\"normal_10AN\" colspan=\"2\" style=\"width:100%\">\n";
      $html .= "          			EXAMEN FISICO&nbsp&nbsp&nbsp;<input checked type=\"radio\" value=\"EX\" name=\"tipoactividad\">\n";
			$html .= "          			EDUCACION&nbsp&nbsp&nbsp;<input type=\"radio\" value=\"ED\" name=\"tipoactividad\">\n";
			$html .= "          		</td>\n";
			$html .= "        		</tr>\n";
			$html .= "    				</table><BR>\n";			
			$html .= "						</fieldset>\n";
			$html .= "						</center>\n";  			
			$html .= "						<table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "						<tr>\n";			
			$html .= "  						<td align=\"center\"><br>\n";
			$html .= "    					<input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"Guardar\">";
			$html .= "    					</td>";			
			$html .= "  					</tr>";
			$html .= "						</table>";			
			$html .= "  					</form>";
      $html .= "					</div>\n";
			
			$html .= "					<div class=\"tab-page\" id=\"cargos\">\n";
			$html .= "				  <h2 class=\"tab\">ADICION DE CARGOS</h2>\n";
			$html .= "				  <script>	tabPane.addTabPage( document.getElementById(\"cargos\")); </script>\n";
			$html .= "						<form name=\"busqueda_cargos\" id=\"busqueda_cargos\"  action=\"".$action['guardarActividad']."\" method=\"post\">\n";
			$html .= "						<center><BR>\n";
			$html .= "						<table border=\"0\" width=\"70%\" align=\"center\" class=\"formulacion_table_list\">\n";
			$html .= "						<tr>\n";
			$html .= "							<td colspan='3' align=\"center\" class=\"formulacion_table_list\" ><b style=\"color:#ffffff\">BUSCADOR DE CARGOS</td>\n";
			$html .= "						</tr>\n";
			$html .= "						<tr>\n";
			$html .= "							<td width='25%' class=\"modulo_list_claro\" align=\"left\">\n";
			$html .= "              	TIPO <select name=\"tipo\" id=\"tipo\" class=\"select\">\n";
			$html .= "                <option value=\"1\" selected>CARGO</option>\n";
			$html .= "                <option value=\"2\">DESCRIPCION</option>\n";
			$html .= "                </select>";
			$html .= "							</td>\n";
			$html .= "          		<td width='65%' id='descripcion' class=\"modulo_list_claro\" align=\"left\">\n";
			$html .= "          			<input type=\"text\" class=\"input-text\" name=\"valor\" id=\"valor\" size=\"50\" value=\"\">\n";
			$html .= "          		</td>\n";
			$html .= "          		<td width='10%' class=\"modulo_list_claro\" align=\"center\">\n";
			$html .= "          			<input type=\"button\" class=\"input-submit\" name=\"buscar\" id=\"buscar\" value=\"BUSCAR\" onclick=\"xajax_BuscarCargos(xajax.getFormValues('busqueda_cargos'),1,0)\">\n";
			$html .= "          		</td>\n";
			$html .= "						</tr>\n";
			$html .= "						</table>\n";
			$html .= "      			<table width='100%' align='center'>";
			$html .= "  					<div id='resultado_cargos'></div>";
			$html .= "						</table>";
			$html .= "						</center>\n";								
			$html .= "						<table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "						<tr>\n";
			$html .= "  						<td align=\"center\"><br>\n";
			$html .= "    					<input class=\"input-submit\" type=\"submit\" name=\"guardar\" value=\"Guardar\">";
			$html .= "    					</td>";
			$html .= "  					</tr>";
			$html .= "						</table>";
			$html .= "  					</form>";														
      $html .= "					</div>\n";
			$html .= "			</div>\n";			
			$html .= "</td></tr>\n";
			$html .= "</table>\n";
			$html .= "<table border=\"0\" width=\"50%\" align=\"center\" >\n";
      $html .= "	<tr>\n";
			$html .= "	<form name=\"volver\" action=\"".$action['volver']."\" method=\"post\">";
			$html .= "  	<td align=\"center\"><br>\n";
			$html .= "    <input class=\"input-submit\" type=\"submit\" name=\"volver\" value=\"Volver\">";
			$html .= "    </td>";
			$html .= "  </form>";
			$html .= "  </tr>";
			$html .= "</table>";
			$html.="
														<script language=\"javaScript\">
														xajax_BuscarCargos(xajax.getFormValues('busqueda_cargos'),1,0);
														</script>";	
			$html .= ThemeCerrarTabla();
			return $html;
		}
		
  }
?>		