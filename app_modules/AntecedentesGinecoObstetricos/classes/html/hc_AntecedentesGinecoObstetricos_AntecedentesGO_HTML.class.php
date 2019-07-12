<?php

/**
* Submodulo de Antecedentes Ginecobstetricos.
*
* Submodulo para manejar los antecedentes ginecobstetricos de un paciente en una evolucion y las diferentes
* evoluciones que se necesiten.
* @author Luis Alejandro Vargas
* @version 1.0
* @package SIIS
* $Id: hc_AntecedentesGinecoObstetricos.class.php,v 1.3 2005/05/12 23:37:49 tizziano Exp $
*/


/**
* AntecedentesGinecoObstetricos
*
* Clase para accesar los metodos privados de la clase de presentación, se compone de metodos publicos para insertar
* en la base de datos, actualizar y borrar de la base de datos, y mostrar la forma de inserción y la consulta del
* submodulo de antecedentes ginecobstetricos.
*/

class AntecedentesGO_HTML
{

/**
* Esta función Inicializa las variable de la clase
*
* @access public
* @return boolean Para identificar que se realizo.
*/
	var $obj;
	
	function AntecedentesGO_HTML($objeto)
	{
      $this->obj=$objeto;
			$this->backcolor="pink";
			$this->backcolorf="#990000";
			return true;
	}
	
	function frmForma($tipo_ant,$cpn_antecedentes,$datosIns,$puntaje,$semana_gestante,$fcp)
	{
		$obj=$this->obj;
		$pfj=$obj->frmPrefijo;

		SessionSetVar("EvolucionHc",$obj->evolucion);
		SessionSetVar("IngresoHc",$obj->ingreso);
		SessionSetVar("RutaImg",GetThemePath());
		SessionSetVar("IdPaciente",$obj->paciente);
		SessionSetVar("TipoPaciente",$obj->tipoidpaciente);
		
		if(empty($this->titulo))
		{
			$this->salida = ThemeAbrirTablaSubModulo('ANTECEDENTES GINECOBTETRICOS');
		}
		else
		{
			$this->salida  = ThemeAbrirTablaSubModulo($this->titulo);
		}
		
		if($this->ban==1)
		{
			$this->salida.= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida.= $this->SetStyle("MensajeError");
			$this->salida.= "      </table><br>";
		}

		$edad=CalcularEdad($obj->datosPaciente['fecha_nacimiento']);
		$this->frmGestacion($semana_gestante,$fcp);
			
			$estilos = "style=\"border-bottom-width:0px;border-left-width:2px;border-right-width:0px;border-top-width:0px;border-style: solid;\""; 
			
			$this->salida = ThemeAbrirTablaSubModulo($titulo1);
			$this->salida .= "	<table width=\"100%\">\n";
			$this->salida .= "		<tr><td>\n";
			$this->salida .= "			<table width=\"30%\" align=\"right\">\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"label_mark\" >MOSTRAR: </td>\n";
			$this->salida .= "					<td align=\"center\" class=\"label_mark\" >\n";
			$this->salida .= "						<a href=\"javascript:ActualizarOpcion('T');MostrarVisibles();MostrarOcultos();\">TODOS</a>\n";			
			$this->salida .= "					</td>\n";
			$this->salida .= "					<td align=\"center\" class=\"label_mark\" $estilos>\n";
			$this->salida .= "						<a href=\"javascript:ActualizarOpcion('V');MostrarVisibles();EsconderOcultos();\">VISIBLES</a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "					<td align=\"center\" class=\"label_mark\" $estilos>\n";
			$this->salida .= "						<a href=\"javascript:ActualizarOpcion('O');MostrarOcultos();EsconderVisibles();\">OCULTOS</a>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "		<tr><td>\n";
			$this->salida .= "			<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "					<td align=\"center\" width=\"44%\" colspan=\"2\">ANTECEDENTES</td>\n";
			$this->salida .= "					<td align=\"center\" >DETALLE</td>\n";
			$this->salida .= "				</tr>\n";
			
			$i = 0;
			$b1 = true;
			$b2 = true;
			$ocultos = "var Aocultos = new Array(";
			$visibles = "var Avisibles = new Array(";
			
			foreach($tipo_ant as $key => $nivel1)
			{
				$j=0;
				$columna = "";					
				if($i % 2 == 0)
				{
					$estilo='modulo_list_oscuro'; $background = "#CCCCCC";
				}
				else
				{
					$estilo='modulo_list_claro'; $background = "#DDDDDD";
				}
				
				foreach($nivel1 as $key1 => $nivel2)
				{
					if($j % 2 == 0)	
					{	
						$est = 'hc_submodulo_list_oscuro'; $estX = 'hc_submodulo_list_claro'; 
					}
					else 
					{
						$est = 'hc_submodulo_list_claro'; $estX = 'hc_submodulo_list_oscuro';
					}
					
					$k = 0;
					$x = 0;
					$tabla = "";
					$tablaO = "";
					foreach($nivel2 as $key2 => $nivel3)
					{
						//if($nivel3['puntaje_asociado'] == -1)
						//{	
							$op = "&nbsp;"; $styl = "style=\"text-transform:capitalize;\"";
							
							if($nivel3['sw_riesgo'] == '0') $op = "NO";
							else if($nivel3['sw_riesgo'] == '1') $op = "SI";
							
							$arregloJs = "new Array('".$nivel3['hctap']."','".$nivel3['hctad']."','$est','$estX','".$i.$j."','".$nivel3['hcid']."'";
							
							$check  = "<a href=\"javascript:CrearArregloCapas(new Array('Antecedente".$i.$j."','Ocultos".$i.$j."'));OcultarAntecedenteGineco(".$arregloJs.",'1'))\" title=\"Ocultar Antecedente\">";
							$check .= "	<img src=\"".GetThemePath()."/images/checkno.png\" height=\"14\" border=\"0\"></a>";
							
							$check1  = "<a href=\"javascript:CrearArregloCapas(new Array('Antecedente".$i.$j."','Ocultos".$i.$j."'));OcultarAntecedenteGineco(".$arregloJs.",'0'))\" title=\"Mostrar Antecedente\">";
							$check1 .= "	<img src=\"".GetThemePath()."/images/checkS.gif\" height=\"14\" width=\"14\" border=\"0\"></a>";
							
							if(!$nivel3['detalle'])
							{
								$k = 0;
								$op = "&nbsp;";
								$check = "&nbsp;";
								$check1 = "&nbsp;";
							}
							if($nivel3['destacar'] == '1') $styl = " style=\"font-weight : bold;text-transform:capitalize;\" ";
							
							$styl1 = " style=\"color:#000066;font-weight : bold; \" ";
							if($nivel3['riesgo'] == $nivel3['sw_riesgo']) $styl1 = " style=\"color:#C40000;font-weight : bold; \" ";
													
							if($nivel3['ocultar'] == '0')
							{
								if($nivel3['detalle'] != "")
									$k = 1;
								
								$tabla .= "						<tr class=\"$est\">\n";
								$tabla .= "							<td align=\"center\" $styl1 width=\"15%\" >$op</td>\n";
								$tabla .= "							<td align=\"justify\" $styl width=\"55%\" >".$nivel3['detalle']."</td>\n";
								$tabla .= "							<td align=\"center\" $styl width=\"20%\">".$nivel3['fecha']."</td>\n";
								$tabla .= "							<td align=\"center\" $styl width=\"10%\">$check</td>\n";
								$tabla .= "						</tr>\n";
							}
							else if($nivel3['ocultar'] == '1')
							{
								$x = 1;
								$tablaO .= "						<tr class=\"$estX\">\n";
								$tablaO .= "							<td align=\"center\" $styl1 width=\"15%\"  >$op</td>\n";
								$tablaO .= "							<td align=\"justify\" $styl width=\"55%\" >".$nivel3['detalle']."</td>\n";
								$tablaO .= "							<td align=\"center\" $styl width=\"20%\">".$nivel3['fecha']."</td>\n";
								$tablaO .= "							<td align=\"center\" $styl width=\"10%\">$check1</td>\n";
								$tablaO .= "						</tr>\n";
							}
						//}
					}
					
					//if($nivel2[$key2]['puntaje_asociado'] == -1)
					//{
						$arregloJs = "new Array('".$nivel2[$key2]['hctap']."','".$nivel2[$key2]['hctad']."','$est','$estX','".$i.$j."')";
						
						$columna .= "					<tr>\n";
						$columna .= "						<td class=\"$est\">\n";
						$columna .= "							<a href=\"javascript:MostrarSpan('d2Container');Iniciar('".$nivel2[$key2]['nombre_tipo']."',new Array('Antecedente".$i.$j."','Ocultos".$i.$j."'),$arregloJs)\" class=\"label\">".$nivel2[$key2]['nombre_tipo']."</a>\n";
						$columna .= "						</td>\n";
						$columna .= "						<td height=\"17\" class=\"$est\">\n";
						$clase = "";
						
						$columna1 = "";
						if($k == 1 || $x == 1)
						{
							$columna1 .= "								<table width=\"100%\" class=\"modulo_table_list\" bgcolor=\"#FFFFFF\">\n";
							$columna1 .= "									<tr class=\"formulacion_table_list\" >\n";
							$columna1 .= "										<td align=\"center\" width=\"15%\" >OP.</td>\n";
							$columna1 .= "										<td align=\"center\" width=\"55%\" >DETALLE</td>\n";
							$columna1 .= "										<td align=\"center\" width=\"20%\" >F. REGIS</td>\n";				
							$columna1 .= "										<td align=\"center\" width=\"10%\" >OCUL</td>\n";
							$columna1 .= "									</tr>\n";
							$columna1 .= "								</table>\n";
							
							$clase = " class=\"modulo_table_list\" bgcolor=\"#FFFFFF\"";
						}
						
						$display = "style=\"display:block\"";
						if($k==0 && $x==1) $display = "style=\"display:none\"";
						
						$columna .= "							<div id=\"XAntecedente".$i.$j."\" $display >$columna1</div>\n";
						$columna .= "								<div id=\"Antecedente".$i.$j."\" style=\"display:block\">\n";
						if($tabla != "")
						{
							$columna .= "									<table width=\"100%\" $clase>\n";
							$columna .= "										$tabla\n";
							$columna .= "									</table>\n";
						}
						$columna .= "								</div>\n";
						$columna .= "								<div id=\"Ocultos".$i.$j."\" style=\"display:none\">\n";	
						if($tablaO != "")
						{
							$columna .= "								<table width=\"100%\" class=\"modulo_table_list\" bgcolor=\"#FFFFFF\">\n";
							$columna .= "									$tablaO\n";
							$columna .= "								</table>\n";
						}
						$columna .= "							</div>\n";
						$columna .= "						</td>\n";
						$columna .= "					</tr>\n";
						
						$b1? $visibles .= "'Antecedente".$i.$j."'": $visibles .= ",'Antecedente".$i.$j."'";
						$b1? $ocultos .= "'Ocultos".$i.$j."'": $ocultos .= ",'Ocultos".$i.$j."'";
						$b1 = false;
						
						$j++;
					//}
				}
				$this->salida .= "		<tr >\n";
				$this->salida .= "			<td rowspan=\"".($j+1)."\" class=\"$estilo\" ><label class=\"label\" width=\"15%\">".$key."</label></td>\n";					
				$this->salida .= "		</tr>\n";
				$this->salida .= "		".$columna;
				$i++;
			}
			$this->salida .= "			</table>\n";
			$this->salida .= "		</td></tr>\n";
			$this->salida .= "	</table><br>\n";
			
			if($obj->cpn)
			{
				$html = "";
				$display="";
				$k=0;
				$recienNacido=0;
				$puntaje_grupo=0;
				
				$paridad=$datosIns[0]['numero_embarazos_previos'];
				if(empty($paridad))
				{
					$paridad=0;
				}

				foreach($cpn_antecedentes as $key => $nivel1)
				{
					$x="";
					$htmlV = "";
					$html1 = "";
				
					if($k % 2 == 0)
					{
						$estilo='hc_submodulo_list_claro';
					}
					else
					{
						$estilo='hc_submodulo_list_oscuro';
					}

					foreach($nivel1 as $key2 => $nivel2)
					{
						if($nivel2['sw_calculado'] == '1')
						{
							if($nivel2['valor_max'])
							{
								if($obj->datosPaciente['edad_paciente']['anos']>=$nivel2['valor_min'] and $obj->datosPaciente['edad_paciente']['anos']<=$nivel2['valor_max'])
								{
									$puntaje_grupo+=$nivel2['puntaje_asociado'];
								}
							}
							else
							{
								if($obj->datosPaciente['edad_paciente']['anos']>=$nivel2['valor_min'])
								{
									$puntaje_grupo+=$nivel2['puntaje_asociado'];
								}
							}
						}
						else if($nivel2['sw_calculado'] == '2')
						{
							if($nivel2['valor_max'])
							{
								if($paridad>=$nivel2['valor_min'] and $paridad<=$nivel2['valor_max'])
								{
									$puntaje_grupo+=$nivel2['puntaje_asociado'];
								}
							}
							else
							{
								if($paridad>=$nivel2['valor_min'])
								{
									$puntaje_grupo+=$nivel2['puntaje_asociado'];
								}
							}
						}
						else if($nivel2['sw_calculado'] == '3')
						{
							if($nivel2['sw_riesgo']=='1')
								$puntaje_grupo+=1;
						}
						
						$op = "&nbsp;"; $styl = "style=\"text-transform:capitalize;\"";
						
						if($nivel2['sw_riesgo'] == '0') $op = "NO";
						else if($nivel2['sw_riesgo'] == '1') $op = "SI";
						
						if(!$nivel2['detalle'])
						{
							$op = "&nbsp;";$check = "&nbsp;";
						}
							
						if($nivel2['destacar'] == '1') $styl = " style=\"font-weight : bold; text-transform:capitalize;\" ";
						
						$styl1 = " style=\"color:#000066;font-weight : bold; \" ";
						if($nivel2['riesgo'] == $nivel2['sw_riesgo']) $styl1 = " style=\"color:#C40000;font-weight : bold; \" ";
						
						if($obj->evolucion==$nivel2['evolucion_id'])
						{
							$arregloj = "new Array('".$nivel2['hctag']."','".$nivel2['pypan']."','".$nivel2['pypang']."','".$nivel2['pypid']."','".$nivel2['detalle']."','".$nivel2['sw_riesgo']."','".$nivel2['destacar']."','capa2$k','$puntaje_grupo','0')";
							
							if($nivel2['pypan']==5 AND ($nivel2['valor_min']==2500 or $nivel2['valor_max']==4000))
							{
								$x = "			<a href=\"javascript:MostrarSpan('PypCpn1');IniciarPyp1('".$nivel2['nombre_tipo']."',$arregloj);Update1($arregloj)\" class=\"label\"><img src=\"".GetThemePath()."/images/editar.png\" border=\"0\"></a>\n";
							}
							else
							{
								$x = "			<a href=\"javascript:MostrarSpan('PypCpn');IniciarPyp('".$nivel2['nombre_tipo']."',$arregloj);Update($arregloj)\" class=\"label\"><img src=\"".GetThemePath()."/images/editar.png\" border=\"0\"></a>\n";
							}
						}
						
						$htmlV .= "									<tr class=\"hc_submodulo_list_claro\">\n";
						$htmlV .= "										<td align=\"center\" $styl1 width=\"15%\"  >$op</td>\n";
						$htmlV .= "										<td align=\"justify\" $styl width=\"55%\" >".$nivel1[$key2]['detalle']."</td>\n";
						$htmlV .= "										<td align=\"center\" $styl width=\"20%\">".$nivel1[$key2]['fecha']."</td>\n";
						$htmlV .= "										<td align=\"center\" $styl width=\"20%\">$x</a></td>\n";
						$htmlV .= "									</tr>\n";	
					}
					
					$html.= "<tr class=\"$estilo\">\n";	
					
					if($nivel1[$key2]['detalle'])
						$display='style=display:block';
					else
						$display='style=display:none';
					
					if($nivel1[$key2]['sw_calculado']=='0' OR $nivel1[$key2]['sw_calculado']=='3')
					{
						$arregloj = "new Array('".$nivel1[$key2]['hctag']."','".$nivel1[$key2]['pypan']."','".$nivel1[$key2]['pypang']."','".$nivel1[$key2]['pypid']."','".$nivel1[$key2]['detalle']."','".$nivel1[$key2]['sw_riesgo']."','".$nivel1[$key2]['destacar']."','capa2$k','$puntaje_grupo','1')";
						
						if($nivel1[$key2]['pypan']==5)
						{
							$html.= "	<td width=\"30%\"><a href=\"javascript:MostrarSpan('PypCpn1');IniciarPyp1('".$nivel1[$key2]['nombre_tipo']."',$arregloj);\" class=\"label\">".$nivel1[$key2]['nombre_tipo']."</a></td>\n";
						}
						else
						{
							$html.= "	<td width=\"30%\"><a href=\"javascript:MostrarSpan('PypCpn');IniciarPyp('".$nivel1[$key2]['nombre_tipo']."',$arregloj);\" class=\"label\">".$nivel1[$key2]['nombre_tipo']."</a></td>\n";
						}
					}
					else
						$html.= "			<td width=\"30%\">".$nivel1[$key2]['nombre_tipo']."</td>\n";

					$html1 .= "								<table width=\"100%\" class=\"modulo_table_list\" bgcolor=\"#FFFFFF\">\n";
					$html1 .= "									<tr class=\"formulacion_table_list\" >\n";
					$html1 .= "										<td align=\"center\" width=\"15%\" >OP.</td>\n";
					$html1 .= "										<td align=\"center\" width=\"55%\" >DETALLE</td>\n";
					$html1 .= "										<td align=\"center\" width=\"20%\" >F. REGIS</td>\n";
					$html1 .= "										<td align=\"center\" width=\"20%\" >OP</td>\n";				
					$html1 .= "									</tr>\n";
					$html1 .= "									$htmlV\n";
					$html1 .= "								</table>\n";	
					
					if($nivel1[$key2]['sw_calculado'] == '1')
					{
						$html .= "	<td>".$obj->datosPaciente['edad_paciente']['anos']."</td>";	
					}	
					else if($nivel1[$key2]['sw_calculado'] == '2')
					{
							$html .= "	<td>".$datosIns[0]['numero_embarazos_previos']."</td>";		
					}
					else
					{
						$html.="<td>";
						$html.="<div id=\"capa2$k\" $display>";
						$html.="	$html1";
						$html."	</div>\n";
						$html.="</td>";
					}
					$html.="</tr>\n";	
					$k++;
				}
				$this->salida .= "<table width=\"100%\" border=\"0\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "	<tr class=\"modulo_table_list_title\">";
				$this->salida .= "		<td align=\"center\">\n";
				$this->salida .= "			ANTECEDENTES PYP_CPN\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "		<td align=\"center\">\n";
				$this->salida .= "			DETALLE\n";
				$this->salida .= "		</td>\n";
				$this->salida .= "	</tr>\n";
				$this->salida .= "	".$html;
				if(empty($puntaje))
				{
					$puntaje=0;
				}
				
				$puntaje+=$puntaje_grupo;
				
				$_SESSION['puntaje_gineco']=$puntaje;
				
				$this->salida .= "	<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida .= "	<td id=\"punta\" class=\"label\" colspan=\"2\" align=\"right\"> PUNTAJE: $puntaje</td>";
				$this->salida .= "	</tr>";
				$this->salida .= "	</table>";
				
				$cols=4;
				$this->salida .= "<div id='PypCpn1' class='d2Container' style=\"display:none\">\n";
				$this->salida .= "	<div id='tituloP1' class='draggable' style=\"	text-transform: uppercase;\"></div>\n";
				$this->salida .= "	<div id='cerrarP1' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('PypCpn1')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
				$this->salida .= "	<div id='errorP1' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
				$this->salida .= "	<div id='ContenedorP1'>\n";
				$this->salida .= "		<form name=\"formacpn1\" action=\"\" method=\"post\">\n";
				$this->salida .= "			<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "					<td>PRESENCIA DEL ANTECEDENTE</td>\n";
				$this->salida .= "					<td class=\"modulo_list_claro\">\n";
				$this->salida .= "           <input type=\"radio\" name=\"decision\" value=\"1\" > <2500\n";
				$this->salida .= "					</td>\n";
				$this->salida .= "					<td class=\"modulo_list_claro\">\n";
				$this->salida .= "            <input type=\"radio\" name=\"decision\" value=\"1\" > >4000\n";
				$this->salida .= "					</td>\n";
				$this->salida .= "					<td class=\"modulo_list_claro\">\n";
				$this->salida .= "            <input type=\"radio\" name=\"decision\" value=\"0\" > OTRO\n";
				$this->salida .= "					</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "					<td colspan=\"$cols\">DETALLE</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
				$this->salida .= "					<td colspan=\"$cols\">\n";
				$this->salida .= "						<textarea class=\"textarea\" name=\"observacion\" rows=\"3\" style=\"width:100%\"></textarea>\n";
				$this->salida .= "					</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
				$this->salida .= "					<td colspan=\"$cols\">\n";
				$this->salida .= "						<input type=\"checkbox\" name=\"resaltar\" class=\"input-text\" value=\"1\"><b>RESALTAR</b>\n";
				$this->salida .= "					</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
				$this->salida .= "					<td colspan=\"$cols\" align=\"center\">\n";
				$this->salida .= "						<input type=\"button\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarDatosPyp1(document.formacpn1)\">\n";
				$this->salida .= "					</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "			</table>\n";
				$this->salida .= "		</form>\n";
				$this->salida .= "	</div>\n";
				$this->salida .= "</div>\n";

				$cols=3;
				$this->salida .= "<div id='PypCpn' class='d2Container' style=\"display:none\">\n";
				$this->salida .= "	<div id='tituloP' class='draggable' style=\"	text-transform: uppercase;\"></div>\n";
				$this->salida .= "	<div id='cerrarP' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('PypCpn')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
				$this->salida .= "	<div id='errorP' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
				$this->salida .= "	<div id='ContenedorP'>\n";
				$this->salida .= "		<form name=\"formacpn\" action=\"\" method=\"post\">\n";
				$this->salida .= "			<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
				$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "					<td>PRESENCIA DEL ANTECEDENTE</td>\n";
				$this->salida .= "					<td class=\"modulo_list_claro\">\n";
				$this->salida .= "            <input type=\"radio\" name=\"decision\" value=\"1\" >SI\n";
				$this->salida .= "					</td>\n";
				$this->salida .= "					<td class=\"modulo_list_claro\">\n";
				$this->salida .= "            <input type=\"radio\" name=\"decision\" value=\"0\" >NO\n";
				$this->salida .= "					</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
				$this->salida .= "					<td colspan=\"$cols\">DETALLE</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
				$this->salida .= "					<td colspan=\"$cols\">\n";
				$this->salida .= "						<textarea class=\"textarea\" name=\"observacion\" rows=\"3\" style=\"width:100%\"></textarea>\n";
				$this->salida .= "					</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
				$this->salida .= "					<td colspan=\"$cols\">\n";
				$this->salida .= "						<input type=\"checkbox\" name=\"resaltar\" class=\"input-text\" value=\"1\"><b>RESALTAR</b>\n";
				$this->salida .= "					</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
				$this->salida .= "					<td colspan=\"$cols\" align=\"center\">\n";
				$this->salida .= "						<input type=\"button\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarDatosPyp(document.formacpn)\">\n";
				$this->salida .= "					</td>\n";
				$this->salida .= "				</tr>\n";
				$this->salida .= "			</table>\n";
				$this->salida .= "		</form>\n";
				$this->salida .= "	</div>\n";
				$this->salida .= "</div>\n";
			}
				
			$this->salida .= "<script>\n";
			$this->salida .= "	".$ocultos.");\n";
			$this->salida .= "	".$visibles.");\n";
			$this->salida .= "	var hiZ = 2;\n";
			$this->salida .= "	var mensaje = '';\n";
			$this->salida .= "	var opcion = 'V';\n";
			$this->salida .= "	var contenedor = '';\n";
			$this->salida .= "	var titulo = '';\n";
			$this->salida .= "	var datosE = new Array();\n";
			$this->salida .= "	var capaActual = new Array();\n";
			$this->salida .= "	var datosP = new Array();\n";
			$this->salida .= "	var capa2='';\n";
			
			$this->salida .= "	function Iniciar(tit,capita,envios)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		datosE = envios;\n";
			$this->salida .= "		capaActual = capita;\n";
			$this->salida .= "		document.getElementById('titulo').innerHTML = '<center>'+tit+'</center>';\n";
			$this->salida .= "		document.getElementById('error').innerHTML = '';\n";
			$this->salida .= "		document.oculta.resaltar.checked = false;\n";
			$this->salida .= "		document.oculta.observacion.value = '';\n";
			$this->salida .= "		document.oculta.decision[0].checked = false;\n";
			$this->salida .= "		document.oculta.decision[1].checked = false;\n";
			$this->salida .= "	 	contenedor = 'd2Container';\n";
			$this->salida .= "		titulo = 'titulo';\n";
			$this->salida .= "		ele = xGetElementById('d2Container');\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop()+24);\n";
			$this->salida .= "		ele = xGetElementById('titulo');\n";
			$this->salida .= "	  xResizeTo(ele,280, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrar');\n";
			$this->salida .= "	  xResizeTo(ele,20, 20);\n";
			$this->salida .= "		xMoveTo(ele, 280, 0);\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function IniciarPyp(tit,arr)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		datosP = arr;\n";
			$this->salida .= "		capa2 = arr[7];\n";
			$this->salida .= "		document.getElementById('tituloP').innerHTML = '<center>'+tit+'</center>';\n";
			$this->salida .= "		document.getElementById('errorP').innerHTML = '';\n";
			$this->salida .= "		document.formacpn.resaltar.checked = false;\n";
			$this->salida .= "		document.formacpn.observacion.value = '';\n";
			$this->salida .= "		document.formacpn.decision[0].checked = false;\n";
			$this->salida .= "		document.formacpn.decision[1].checked = false;\n";
			$this->salida .= "	 	contenedor = 'PypCpn';\n";
			$this->salida .= "		titulo = 'tituloP';\n";
			$this->salida .= "		ele = xGetElementById(contenedor);\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop()+24);\n";
			$this->salida .= "		ele = xGetElementById(titulo);\n";
			$this->salida .= "	  xResizeTo(ele,280, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrarP');\n";
			$this->salida .= "	  xResizeTo(ele,20, 20);\n";
			$this->salida .= "		xMoveTo(ele, 280, 0);\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function IniciarPyp1(tit,arr)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		datosP = arr;\n";
			$this->salida .= "		capa2 = arr[7];\n";
			$this->salida .= "		document.getElementById('tituloP1').innerHTML = '<center>'+tit+'</center>';\n";
			$this->salida .= "		document.getElementById('errorP1').innerHTML = '';\n";
			$this->salida .= "		document.formacpn1.resaltar.checked = false;\n";
			$this->salida .= "		document.formacpn1.observacion.value = '';\n";
			$this->salida .= "		document.formacpn1.decision[0].checked = false;\n";
			$this->salida .= "		document.formacpn1.decision[1].checked = false;\n";
			$this->salida .= "		document.formacpn1.decision[2].checked = false;\n";
			$this->salida .= "	 	contenedor = 'PypCpn1';\n";
			$this->salida .= "		titulo = 'tituloP1';\n";
			$this->salida .= "		ele = xGetElementById(contenedor);\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop()+24);\n";
			$this->salida .= "		ele = xGetElementById(titulo);\n";
			$this->salida .= "	  xResizeTo(ele,280, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrarP1');\n";
			$this->salida .= "	  xResizeTo(ele,20, 20);\n";
			$this->salida .= "		xMoveTo(ele, 280, 0);\n";
			$this->salida .= "	}\n";
			
			
			$this->salida .= "	function Update(arr)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		datos = arr;\n";
			$this->salida .= "		capa2 = arr[7];\n";
			$this->salida .= "		if(datos[6]!='')\n";
			$this->salida .= "			document.formacpn.resaltar.checked = true;\n";
			$this->salida .= "		else\n";
			$this->salida .= "			document.formacpn.resaltar.checked = false;\n";
			$this->salida .= "		if(datos[4]!='')\n";
			$this->salida .= "			document.formacpn.observacion.value = datos[4];\n";
			$this->salida .= "		else\n";
			$this->salida .= "			document.formacpn.observacion.value ='' ;\n";
			$this->salida .= "		if(datos[5]=='1')\n";
			$this->salida .= "		{\n";
			$this->salida .= "			document.formacpn.decision[0].checked = true;\n";
			$this->salida .= "			document.formacpn.decision[1].checked = false;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		else if(datos[5]=='0')\n";
			$this->salida .= "		{\n";
			$this->salida .= "			document.formacpn.decision[0].checked = false;\n";
			$this->salida .= "			document.formacpn.decision[1].checked = true;\n";
			$this->salida .= "		}\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function Update1(arr)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		datos = arr;\n";
			$this->salida .= "		capa2 = arr[7];\n";
			$this->salida .= "		if(datos[6]!='')\n";
			$this->salida .= "			document.formacpn1.resaltar.checked = true;\n";
			$this->salida .= "		else\n";
			$this->salida .= "			document.formacpn1.resaltar.checked = false;\n";
			$this->salida .= "		if(datos[4]!='')\n";
			$this->salida .= "			document.formacpn1.observacion.value = datos[4];\n";
			$this->salida .= "		else\n";
			$this->salida .= "			document.formacpn1.observacion.value ='' ;\n";
			$this->salida .= "		if(datos[5]=='1')\n";
			$this->salida .= "		{\n";
			$this->salida .= "			document.formacpn1.decision[0].checked = true;\n";
			$this->salida .= "			document.formacpn1.decision[1].checked = false;\n";
			$this->salida .= "			document.formacpn1.decision[2].checked = false;\n";
			$this->salida .= "		}\n";
			$this->salida .= "		else if(datos[5]=='0')\n";
			$this->salida .= "		{\n";
			$this->salida .= "			document.formacpn1.decision[0].checked = false;\n";
			$this->salida .= "			document.formacpn1.decision[1].checked = true;\n";
			$this->salida .= "		}\n";
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
			$this->salida .= "	{}\n";
			
			$this->salida .= "	function MostrarSpan(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		e = xGetElementById(Seccion);\n";
			$this->salida .= "		e.style.display = \"\";\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function Cerrar(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		e = xGetElementById(Seccion);\n";
			$this->salida .= "		e.style.display = \"none\";\n";
			$this->salida .= "	}\n";
			$this->salida .= "	function CrearArregloCapas(capita)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		capaActual = capita;\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function EvaluarDatos(objeto)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		mensaje = '';\n";
			$this->salida .= "		var vsino = ''; \n";
			$this->salida .= "		var vresaltar = '0';\n";
			$this->salida .= "		var vobservacion = objeto.observacion.value;\n";
			$this->salida .= "		if(objeto.decision[0].checked) \n";
			$this->salida .= "			vsino = objeto.decision[0].value;\n";
			$this->salida .= "			else if(objeto.decision[1].checked)\n";
			$this->salida .= "				vsino = objeto.decision[1].value;\n";
			$this->salida .= "		if(objeto.resaltar.checked) vresaltar = objeto.resaltar.value;\n";
			$this->salida .= "		if(vsino == '')\n";
			$this->salida .= "			mensaje = 'SE DEBE ESCOGER SI, EL PACIENTE PRESENTA O NO EL ANTECENTE';\n";
			$this->salida .= "		else if(vobservacion == '')\n";
			$this->salida .= "			mensaje = 'SE DEBE INGRESAR EL DETALLE DE LA PATOLOGIA';\n";
			$this->salida .= "		document.getElementById('error').innerHTML = '<center>'+mensaje+'</center>';\n";
			$this->salida .= "		if(mensaje == '')\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			datosE[5] = vsino;\n";
			$this->salida .= "			datosE[6] = vobservacion;\n";
			$this->salida .= "			datosE[7] = vresaltar;\n";
			$this->salida .= "			CrearAntecedentesGineco(datosE);\n";
			$this->salida .= "		}\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function EvaluarDatosPyp(objeto)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		mensaje = '';\n";
			$this->salida .= "		var vsino = ''; \n";
			$this->salida .= "		var vresaltar = '0';\n";
			$this->salida .= "		var vobservacion = objeto.observacion.value;\n";
			$this->salida .= "		if(objeto.decision[0].checked) \n";
			$this->salida .= "			vsino = objeto.decision[0].value;\n";
			$this->salida .= "			else if(objeto.decision[1].checked)\n";
			$this->salida .= "				vsino = objeto.decision[1].value;\n";
			$this->salida .= "		if(objeto.resaltar.checked) vresaltar = objeto.resaltar.value;\n";
			$this->salida .= "		if(vsino == '')\n";
			$this->salida .= "			mensaje = 'SE DEBE ESCOGER SI, EL PACIENTE PRESENTA O NO EL ANTECENTE';\n";
			$this->salida .= "		else if(vobservacion == '')\n";
			$this->salida .= "			mensaje = 'SE DEBE INGRESAR EL DETALLE DE LA PATOLOGIA';\n";
			$this->salida .= "		document.getElementById('errorP').innerHTML = '<center>'+mensaje+'</center>';\n";
			$this->salida .= "		if(mensaje == '')\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			datosP[10] = vsino;\n";
			$this->salida .= "			datosP[11] = vobservacion;\n";
			$this->salida .= "			datosP[12] = vresaltar;\n";
			$this->salida .= "			CrearAntecedentesGinecoPyp(datosP);\n";
			$this->salida .= "		}\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function EvaluarDatosPyp1(objeto)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		mensaje = '';\n";
			$this->salida .= "		var vsino = ''; \n";
			$this->salida .= "		var vresaltar = '0';\n";
			$this->salida .= "		var vobservacion = objeto.observacion.value;\n";
			$this->salida .= "		if(objeto.decision[0].checked) \n";
			$this->salida .= "			vsino = objeto.decision[0].value;\n";
			$this->salida .= "			else if(objeto.decision[1].checked)\n";
			$this->salida .= "				vsino = objeto.decision[1].value;\n";
			$this->salida .= "			else if(objeto.decision[2].checked)\n";
			$this->salida .= "				vsino = objeto.decision[2].value;\n";
			$this->salida .= "		if(objeto.resaltar.checked) vresaltar = objeto.resaltar.value;\n";
			$this->salida .= "		if(vsino == '')\n";
			$this->salida .= "			mensaje = 'SE DEBE ESCOGER SI, EL PACIENTE PRESENTA O NO EL ANTECENTE';\n";
			$this->salida .= "		else if(vobservacion == '')\n";
			$this->salida .= "			mensaje = 'SE DEBE INGRESAR EL DETALLE DE LA PATOLOGIA';\n";
			$this->salida .= "		document.getElementById('errorP1').innerHTML = '<center>'+mensaje+'</center>';\n";
			$this->salida .= "		if(mensaje == '')\n";
			$this->salida .= "		{ \n";
			$this->salida .= "			datosP[10] = vsino;\n";
			$this->salida .= "			datosP[11] = vobservacion;\n";
			$this->salida .= "			datosP[12] = vresaltar;\n";
			$this->salida .= "			CrearAntecedentesGinecoPyp(datosP);\n";
			$this->salida .= "		}\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function ActualizarAntecedentes(html)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		Cerrar('d2Container');\n";
			$this->salida .= "		resultado  = jsrsArrayFromString( html, 'ç' );";
			$this->salida .= "		document.getElementById(capaActual[0]).innerHTML = resultado[0];\n";
			$this->salida .= "		document.getElementById(capaActual[1]).innerHTML = resultado[1];\n";
			$this->salida .= "		document.getElementById('X'+capaActual[0]).innerHTML = resultado[2];\n";
			$this->salida .= "		if(opcion == 'V' && resultado[0] == \"\") Cerrar('X'+capaActual[0]);\n";
			$this->salida .= "		if(opcion == 'O' && resultado[1] == \"\") Cerrar('X'+capaActual[0]);\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function AntecenteGinecoPyp(html)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		Cerrar('PypCpn');\n";
			$this->salida .= "		Cerrar('PypCpn1');\n";
			$this->salida .= "		resultado  = jsrsArrayFromString( html, 'ç' );";
			$this->salida .= "		document.getElementById(capa2).innerHTML = resultado[0]\n";
			$this->salida .= "		document.getElementById('punta').innerHTML = resultado[1]\n";
			$this->salida .= "		MostrarSpan(capa2)\n";
			$this->salida .= "		MostrarSpan('punta')\n";
			$this->salida .= "	}\n";

			$this->salida .= "		function MostrarOcultos()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			for(i=0; i<Aocultos.length; i++)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Aocultos[i]);\n";
			$this->salida .= "				e.style.display = \"block\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function EsconderOcultos()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			for(i=0; i<Aocultos.length; i++)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Aocultos[i]);\n";
			$this->salida .= "				e.style.display = \"none\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function MostrarVisibles()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			for(i=0; i<Avisibles.length; i++)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Avisibles[i]);\n";
			$this->salida .= "				e.style.display = \"block\";\n";
			$this->salida .= "				try\n";
			$this->salida .= "				{\n";
			$this->salida .= "					html = document.getElementById(Avisibles[i]).innerHTML;\n";
			$this->salida .= "					if( html.substring(10,11) != \"\" && opcion == 'V')\n";
			$this->salida .= "					{\n";
			$this->salida .= "						f = xGetElementById('X'+Avisibles[i]);\n";
			$this->salida .= "						f.style.display = \"block\";\n";
			$this->salida .= "					}\n";
			$this->salida .= "					else if(opcion == 'T')\n";
			$this->salida .= "					{\n";
			$this->salida .= "						f = xGetElementById('X'+Avisibles[i]);\n";
			$this->salida .= "						f.style.display = \"block\";\n";
			$this->salida .= "					}\n";
			$this->salida .= "					else \n";
			$this->salida .= "					{\n";
			$this->salida .= "						f = xGetElementById('X'+Avisibles[i]);\n";
			$this->salida .= "						f.style.display = \"none\";\n";
			$this->salida .= "					}\n";
			$this->salida .= "				}\n";
			$this->salida .= "				catch(error){}\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function EsconderVisibles()\n";
			$this->salida .= "		{\n";
			$this->salida .= "			for(i=0; i<Avisibles.length; i++)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e = xGetElementById(Avisibles[i]);\n";
			$this->salida .= "				e.style.display = \"none\";\n";
			$this->salida .= "				html = document.getElementById(Aocultos[i]).innerHTML;";
			$this->salida .= "				if( html.substring(9,10) == \"\")\n";
			$this->salida .= "				{\n";
			$this->salida .= "					f = xGetElementById('X'+Avisibles[i]);\n";
			$this->salida .= "					f.style.display = \"none\";\n";
			$this->salida .= "				}\n";
			$this->salida .= "				else\n";
			$this->salida .= "				{\n";
			$this->salida .= "					f = xGetElementById('X'+Avisibles[i]);\n";
			$this->salida .= "					f.style.display = \"block\";\n";
			$this->salida .= "				}\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "		function ActualizarOpcion(op)\n";
			$this->salida .= "		{opcion = op;}\n";
			$this->salida .= "</script>";
			$this->salida .= "<div id='d2Container' class='d2Container' style=\"display:none\">\n";
			$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;\"></div>\n";
			$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='error' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
			$this->salida .= "	<div id='d2Contents'>\n";
			$this->salida .= "		<form name=\"oculta\" action=\"\" method=\"post\">\n";
			$this->salida .= "			<table width=\"100%\" align=\"center\" class=\"modulo_table_list\">\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "					<td  >PRESENCIA DEL ANTECEDENTE</td>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\">\n";
			$this->salida .= "            <input type=\"radio\" name=\"decision\" value=\"1\" >SI\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "					<td class=\"modulo_list_claro\">\n";
			$this->salida .= "            <input type=\"radio\" name=\"decision\" value=\"0\" >NO\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_table_list_title\">\n";
			$this->salida .= "					<td colspan=\"3\">DETALLE</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "					<td colspan=\"3\">\n";
			$this->salida .= "						<textarea class=\"textarea\" name=\"observacion\" rows=\"3\" style=\"width:100%\"></textarea>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "					<td colspan=\"3\">\n";
			$this->salida .= "						<input type=\"checkbox\" name=\"resaltar\" class=\"input-text\" value=\"1\"><b>RESALTAR</b>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_list_claro\">\n";
			$this->salida .= "					<td colspan=\"3\" align=\"center\">\n";
			$this->salida .= "						<input type=\"button\" class=\"input-submit\"name=\"aceptar\" value=\"Aceptar\" onclick=\"EvaluarDatos(document.oculta)\">\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table>\n";
			$this->salida .= "		</form>\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "</div>\n";
			
			$accion1=ModuloHCGetURL($obj->evolucion,$obj->paso,0,'',false,array('accion'.$pfj=>'RiesgoBiopsicosocial'));
			if($pfj=='AtencionCPN')
				$accion2=ModuloHCGetURL($obj->evolucion,$obj->paso,0,'',false,array('accion'.$pfj=>'frmInscripcionCPN','programa'.$pfj=>$programa));
			else
				$accion2=ModuloHCGetURL($obj->evolucion,-1,0,'',false);
				
			$this->salida.="<form name=\"formasig$pfj\" action=\"$accion1\" method=\"post\">";
			$this->salida.="<br><table width=\"100%\" cellspacing=\"5\" align=\"center\">";
			$this->salida.="<tr>";
			$this->salida.="			<td align=\"right\"><input type=\"submit\" name=\"siguiente\" value=\"SIGUIENTE\" class=\"input-submit\"></td>";
			$this->salida.="</form>";
			$this->salida.='<form name="formavolver'.$pfj.'" action="'.$accion2.'" method="post">';
			$this->salida.="			<td align=\"left\"><input type=\"submit\" name=\"volver\" value=\"VOLVER\" class=\"input-submit\"></td>";
			$this->salida.="</tr>";
			$this->salida.="</form>";
			$this->salida.="</table>";
			
			$this->salida .= ThemeCerrarTablaSubModulo();
			return $this->salida;
	}
	
	function frmGestacion($semana,$fecha)
	{
		$this->salida.="<table  align=\"center\" border=\"0\"  width=\"80%\" cellpadding=\"0\" cellspacing=\"2\">";
		$this->salida.="	<tr>";
		$this->salida.="		<td width=\"25%\" class=\"modulo_table_list_title\">SEMANA DE GESTACION</td>";
		$this->salida.="		<td width=\"25%\" class=\"modulo_table_list_title\">FECHA PROBLABLE DE PARTO</td>";
		$this->salida.="  </tr>";
		$this->salida.="	<tr>";
		$this->salida.="		<td width=\"25%\" class=\"hc_table_submodulo_list_title\"><label class=\"label\">$semana</label></td>";
		$this->salida.="		<td width=\"25%\" class=\"hc_table_submodulo_list_title\"><label class=\"label\">$fecha</label></td>";
		$this->salida.="  </tr>";
		$this->salida.="</table><br>";
		return true;
	}
	
	function SetStyle($campo)
	{
		$obj=$this->obj;
		$pfj=$obj->frmPrefijo;
		
		if ($this->frmError[$campo]||$campo=="MensajeError")
		{
			if ($campo=="MensajeError")
			{
				return ("<tr><td align=\"center\" class=\"label_error\">".$this->frmError["MensajeError"]."</td></tr>");
			}
			return ("label_error");
		}
		return ("label");
	}
	
	function PartirFecha($fecha)
	{
		$a=explode('-',$fecha);
		$b=explode(' ',$a[2]);
		$c=explode(':',$b[1]);
		$d=explode('.',$c[2]);
		return $a[0].'-'.$a[1].'-'.$b[0].' '.$c[0].':'.$c[1].':'.$d[0];
	}
	
}

?>
