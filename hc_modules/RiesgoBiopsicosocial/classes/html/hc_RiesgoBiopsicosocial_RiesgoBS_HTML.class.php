<?php
	/********************************************************************************* 
 	* $Id: hc_RiesgoBiopsicosocial_RiesgoBS_HTML.class.php,v 1.3 2007/02/01 20:51:09 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_RiesgoBiopsicosocial
	* 
 	**********************************************************************************/

	class RiesgoBS_HTML
	{
		function RiesgoBS_HTML()
		{
			$this->backcolorf="#990000";
			return true;
		}

		function frmHistoria($registro_riesgo)
		{
			if(sizeof($registro_riesgo)>0)
			{
				
				$k=0;
				$cont=0;
				$evo=array();
				
				$r_ini=array(0,28,33);
				$r_fin=array(27,32,40);
				
				foreach($registro_riesgo as $key1=>$valor1)
				{
					if($k%2==0)
						$estilo="hc_submodulo_list_claro";
					else
						$estilo="hc_submodulo_list_oscuro";
						
					$salida.="			<tr class=\"$estilo\" width=\"100%\">";
					$salida.="			<td align=\"left\" $estilo width=\"90%\"><label class=\"label\">".$key1."</label></td>";
					
					if($valor1[$key2][evolucion_id]>$cont)
					{
						$cont=$valor1[$key2][evolucion_id];
						$evo[]=$valor1[$key2][evolucion_id];
						for($j=0;$j<3;$j++)
							if($valor1[$key2][semana]>=$r_ini[$j] AND $valor1[$key2][semana]<=$r_fin[$j])
							{
								$semana[]=$r_ini[$j]."-".$r_fin[$j];
								break;
							}
					}
					
					foreach($valor1 as $key2=>$valor2)
					{
						if($valor2[puntaje])
						{
							if($cont==$valor2[evolucion_id])
							{
								$salida.="		<td align=\"center\" $estilo><label class=\"label_error\">Si - ".$valor2[puntaje]."</label></td>";
								$salida.="		<td align=\"center\" $estilo>&nbsp;</td>";
							}
							else
							{
								$salida.="		<td align=\"center\" $estilo>&nbsp;</td>";
								$salida.="		<td align=\"center\" $estilo><label class=\"label_error\">Si - ".$valor2[puntaje]."</label></td>";
							}
						}
						elseif($valor2[grupo_id]==1)
						{
							if($valor2[valor]==0)
								$riesgop="<label class=\"label\">Ausente</label>";
							else
								$riesgop="<label class=\"label_error\">Intenso</label>";
							$salida.="			<td align=\"center\" $estilo>$riesgop</td>";
						}
						elseif($valor2[grupo_id]==2)
						{
							switch($valor2[valor])
							{
								case 0:
									$riesgop="<label class=\"label\">Casi Siempre</label>";
								break;
								case 1:
									$riesgop="<label class=\"label\">A veces</label>";
								break;
								case 2:
									$riesgop="<label class=\"label_error\">Nunca</label>";
								break;
							}
							$salida.="			<td align=\"center\" $estilo>$riesgop</td>";
						}
					}
					$salida.="			<tr>";
					$k++;
				}
				$this->salida.="		<br><table align=\"center\" border=\"1\" class=\"hc_table_submodulo_list\" width=\"100%\">";
				$this->salida.="			<tr class=\"modulo_table_list_title\" width=\"100%\">";
				$this->salida.="				<td align=\"center\" width=\"80%\">RIESGO</td>";
				for($i=0;$i<sizeof($semana);$i++)
					$this->salida.="				<td align=\"center\" width=\"10%\">".$semana[$i]."</td>";
				$this->salida.="			<tr>";
				$this->salida.="".		$salida;
				$this->salida.="		</table>";

			}
			else
				return false;
				
			return $this->salida;
		}
		
		function frmConsulta($registro_riesgo)
		{
		
			if(sizeof($registro_riesgo)>0)
			{
				
				$k=0;
				$cont=0;
				$evo=array();
				
				$r_ini=array(0,28,33);
				$r_fin=array(27,32,40);
				
				foreach($registro_riesgo as $key1=>$valor1)
				{
					if($k%2==0)
						$estilo="hc_submodulo_list_claro";
					else
						$estilo="hc_submodulo_list_oscuro";
						
					$salida.="			<tr class=\"$estilo\" width=\"100%\">";
					$salida.="			<td align=\"left\" $estilo width=\"90%\"><label class=\"label\">".$key1."</label></td>";
					
					if($valor1[$key2][evolucion_id]>$cont)
					{
						$cont=$valor1[$key2][evolucion_id];
						$evo[]=$valor1[$key2][evolucion_id];
						for($j=0;$j<3;$j++)
							if($valor1[$key2][semana]>=$r_ini[$j] AND $valor1[$key2][semana]<=$r_fin[$j])
							{
								$semana[]=$r_ini[$j]."-".$r_fin[$j];
								break;
							}
					}
					
					foreach($valor1 as $key2=>$valor2)
					{
						if($valor2[puntaje])
						{
							if($cont==$valor2[evolucion_id])
							{
								$salida.="		<td align=\"center\" $estilo><label class=\"label_error\">Si - ".$valor2[puntaje]."</label></td>";
								$salida.="		<td align=\"center\" $estilo>&nbsp;</td>";
							}
							else
							{
								$salida.="		<td align=\"center\" $estilo>&nbsp;</td>";
								$salida.="		<td align=\"center\" $estilo><label class=\"label_error\">Si - ".$valor2[puntaje]."</label></td>";
							}
						}
						elseif($valor2[grupo_id]==1)
						{
							if($valor2[valor]==0)
								$riesgop="<label class=\"label\">Ausente</label>";
							else
								$riesgop="<label class=\"label_error\">Intenso</label>";
							$salida.="			<td align=\"center\" $estilo>$riesgop</td>";
						}
						elseif($valor2[grupo_id]==2)
						{
							switch($valor2[valor])
							{
								case 0:
									$riesgop="<label class=\"label\">Casi Siempre</label>";
								break;
								case 1:
									$riesgop="<label class=\"label\">A veces</label>";
								break;
								case 2:
									$riesgop="<label class=\"label_error\">Nunca</label>";
								break;
							}
							$salida.="			<td align=\"center\" $estilo>$riesgop</td>";
						}
					}
					$salida.="			<tr>";
					$k++;
				}
				$this->salida.="		<br><table align=\"center\" border=\"1\" class=\"hc_table_submodulo_list\" width=\"100%\">";
				$this->salida.="			<tr class=\"modulo_table_list_title\" width=\"100%\">";
				$this->salida.="				<td align=\"center\" width=\"80%\">RIESGO</td>";
				for($i=0;$i<sizeof($semana);$i++)
					$this->salida.="				<td align=\"center\" width=\"10%\">".$semana[$i]."</td>";
				$this->salida.="			<tr>";
				$this->salida.="".		$salida;
				$this->salida.="		</table>";

			}
			else
				return false;
				
			return $this->salida;
		}
		
		function frmForma($riesgos_bp,$registro_riesgo,$grupo_riesgo,$semanas,$puntaje,$r_ini,$r_fin,$semana_gestante,$fcp,$evolucion)
		{
			
			$evolucion=SessionGetVar("Evolucion");
			$programa=SessionGetVar("Programa");
			$inscripcion=SessionGetVar("Inscripcion_$programa");
			$pfj=SessionGetVar("Prefijo");
			$paso=SessionGetVar("Paso");
			
			$num_reg=$semanas;
			if(empty($num_reg))
			{
				$num_reg=0;
			}
			
			$this->salida.= ThemeAbrirTablaSubModulo('RIESGO BIOPSICOSOCIAL');

			if($this->ban=1)
			{
				$this->salida .= "      <table border=\"0\" width=\"100%\" align=\"center\">";
				$this->salida .= $this->SetStyle("MensajeError");
				$this->salida .= "      </table><br>"; 
			}
			
			$this->frmGestacion($semana_gestante,$fcp);

			$accion=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'RiesgoBiopsicosocial'));
			
			$this->salida.="<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
			$this->salida.="		<table align=\"center\" border=\"0\"  width=\"100%\">";
			$this->salida.="			<tr class=\"modulo_table_list_title\" width=\"100%\">";
			$this->salida.="				<td align=\"center\" width=\"60%\" colspan=\"2\">&nbsp;&nbsp;</td>";
			
			for($i=0;$i<3;$i++)
			{
				$ban=0;
				for($a=0;$a<sizeof($registro_riesgo);$a++)
				{
					if($registro_riesgo[$a][semana]>=$r_ini[$i] and $registro_riesgo[$a][semana]<=$r_fin[$i])
					{
						$this->salida.="		<td align=\"center\" width=\"10%\">".$r_ini[$i]."-".$r_fin[$i]."</td>";
						$this->salida.="		<input type=\"hidden\" name=\"semana$pfj\" value=\"".$r_ini[$i]."-".$r_fin[$i]."\">";	
						$ban=1;
						break;
					}
				}
				if($ban==0 AND $semana_gestante>=$r_ini[$i] and $semana_gestante<=$r_fin[$i])
				{
					$this->salida.="		<td align=\"center\" width=\"10%\">".$r_ini[$i]."-".$r_fin[$i]."</td>";
					$this->salida.="		<input type=\"hidden\" name=\"semana$pfj\" value=\"".$r_ini[$i]."-".$r_fin[$i]."\">";	
				}
			}
			$this->salida.="			</tr>";
			
			$nombre1="";
			
			for($i=0;$i<sizeof($riesgos_bp);$i++)
			{
				if($k%2==0)
					$estilo="class=\"modulo_list_claro\"";
				else
					$estilo="class=\"modulo_list_oscuro\"";
				
				$nombre1="nombre_riesgo".$pfj."[]";
				
				if($i<8)
				{
					$this->salida.="			<tr width=\"100%\">";
					if($i==0)
					{
						$this->salida.="			<td class=\"modulo_table_list_title\" align=\"center\" width=\"5%\" rowspan=\"8\">CONDICIONES ASOCIADAS</td>";
					}
					$this->salida.="				<td align=\"left\" width=\"55%\" height=\"27\" $estilo><label class=\"label\">".$riesgos_bp[$i][descripcion]."</label></td>";
					
					for($j=0;$j<3;$j++)
					{
						$ban=0;
						for($a=0;$a<sizeof($registro_riesgo);$a++)
						{
							if($registro_riesgo[$a][semana]>=$r_ini[$j] and $registro_riesgo[$a][semana]<=$r_fin[$j])
							{
								$ban1=0;
								for($s=0;$s<sizeof($registro_riesgo);$s++)
								{
									if($riesgos_bp[$i][riesgo_id]==$registro_riesgo[$s][riesgo_id] and ($registro_riesgo[$s][semana]>=$r_ini[$j] and $registro_riesgo[$s][semana]<=$r_fin[$j]))
									{
										if($registro_riesgo[$a][evolucion_id]==$evolucion AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
										{
											$this->salida.="			<td align=\"center\" $estilo><label class=\"label_error\">".$registro_riesgo[$s][valor]."</label> <input type=\"checkbox\" name=\"$nombre1\" value=\"".$riesgos_bp[$i][puntaje]."".$riesgos_bp[$i][riesgo_id]."\" checked></td>";
										}
										else
										{
											$this->salida.="			<td align=\"center\" $estilo><label class=\"label_error\">".$registro_riesgo[$s][valor]."</label> <img src=\"".GetThemePath()."/images/checksi.png\"></td>";
										}
										$ban1=1;
										break;
									}
								}
								
								if($ban1==0)
								{
									$flag=0;
									for($s=0;$s<sizeof($registro_riesgo);$s++)
									{
										if($registro_riesgo[$s][evolucion_id]!=$evolucion AND ($registro_riesgo[$s][semana]>=$r_ini[$j] AND $registro_riesgo[$s][semana]<=$r_fin[$j]))
										{
											$flag=1;
											break;
										}
									}
									
									if($flag==0 AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
									{
										if(strtolower($riesgos_bp[$i][descripcion])=='embarazo prolongado (>42 sem)')
											$this->salida.="			<td align=\"center\" $estilo> </td>";
										else	
											$this->salida.="			<td align=\"center\" $estilo><input type=\"checkbox\" name=\"$nombre1\" value=\"".$riesgos_bp[$i][puntaje]."".$riesgos_bp[$i][riesgo_id]."\"></td>";
									}
									else
										$this->salida.="			<td align=\"center\" $estilo>&nbsp;</td>";
								}
								$ban=1;
								break;
							}
						}
						if($ban==0 AND $semana_gestante>=$r_ini[$j] and $semana_gestante<=$r_fin[$j])
						{
							$ban1=0;
							for($s=0;$s<sizeof($registro_riesgo);$s++)
							{
								if($riesgos_bp[$i][riesgo_id]==$registro_riesgo[$a][riesgo_id] and ($registro_riesgo[$s][semana]>=$r_ini[$j] and $registro_riesgo[$s][semana]<=$r_fin[$j]))
								{
									if($registro_riesgo[$s][evolucion_id]==$evolucion AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
									{
										$this->salida.="			<td align=\"center\" $estilo><label class=\"label_error\">".$registro_riesgo[$s][valor]."</label> <input type=\"checkbox\" name=\"$nombre1\" value=\"".$riesgos_bp[$i][puntaje]."".$riesgos_bp[$i][riesgo_id]."\" checked></td>";
									}
									else
									{
										$this->salida.="			<td align=\"center\" $estilo><label class=\"label_error\">".$registro_riesgo[$s][valor]."</label> <img src=\"".GetThemePath()."/images/checksi.png\"></td>";
									}
									$ban1=1;
									break;
								}
							}
							
							if($ban1==0)
							{
								$flag=0;
								for($s=0;$s<sizeof($registro_riesgo);$s++)
								{
									if($registro_riesgo[$a][evolucion_id]!=$evolucion AND ($registro_riesgo[$s][semana]>=$r_ini[$j] AND $registro_riesgo[$s][semana]<=$r_fin[$j]))
									{
										$flag=1;
										break;
									}
								}
								
								if($flag==0 AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
								{
									if(strtolower($riesgos_bp[$i][descripcion])=='embarazo prolongado (>42 sem)')
										$this->salida.="			<td align=\"center\" $estilo> </td>";
									else	
										$this->salida.="			<td align=\"center\" $estilo><input type=\"checkbox\" name=\"$nombre1\" value=\"".$riesgos_bp[$i][puntaje]."".$riesgos_bp[$i][riesgo_id]."\"></td>";
								}
								else
									$this->salida.="			<td align=\"center\" $estilo>&nbsp;2</td>";
							}
						}
					}
					$this->salida.="			</tr>";
				}
				else if($i>=8)
				{
					$this->salida.="			<tr width=\"100%\">";
					if($i==8)
					{
						$this->salida.="				<td class=\"modulo_table_list_title\" align=\"center\" rowspan=\"".(sizeof($riesgos_bp)-8)."\">EMBARAZO ACTUAL</td>";
					}
					$this->salida.="				<td align=\"left\"  height=\"27\" $estilo><label class=\"label\">".$riesgos_bp[$i][descripcion]."</label></td>";
					for($j=0;$j<3;$j++)
					{
						if($riesgos_bp[$i][puntaje])
						{
							$ban=0;
							for($a=0;$a<sizeof($registro_riesgo);$a++)
							{
								if($registro_riesgo[$a][semana]>=$r_ini[$j] and $registro_riesgo[$a][semana]<=$r_fin[$j])
								{
									$ban1=0;
									for($s=0;$s<sizeof($registro_riesgo);$s++)
									{
										if($riesgos_bp[$i][riesgo_id]==$registro_riesgo[$s][riesgo_id] and ($registro_riesgo[$s][semana]>=$r_ini[$j] and $registro_riesgo[$s][semana]<=$r_fin[$j]))
										{
											if($registro_riesgo[$a][evolucion_id]==$evolucion AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
											{
												$this->salida.="			<td align=\"center\" $estilo><label class=\"label_error\">".$registro_riesgo[$s][valor]."</label> <input type=\"checkbox\" name=\"$nombre1\" value=\"".$riesgos_bp[$i][puntaje]."".$riesgos_bp[$i][riesgo_id]."\" checked></td>";
											}
											else
											{
												$this->salida.="			<td align=\"center\" $estilo><label class=\"label_error\">".$registro_riesgo[$s][valor]."</label> <img src=\"".GetThemePath()."/images/checksi.png\"></td>";
											}
											$ban1=1;
											break;	
										}
									}
									
									if($ban1==0)
									{
										$flag=0;
										for($s=0;$s<sizeof($registro_riesgo);$s++)
										{
											if($registro_riesgo[$s][evolucion_id]!=$evolucion AND ($registro_riesgo[$s][semana]>=$r_ini[$j] AND $registro_riesgo[$s][semana]<=$r_fin[$j]))
											{
												$flag=1;
												break;
											}
										}
										
										if($flag==0 AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
										{
											if(strtolower($riesgos_bp[$i][descripcion])=='embarazo prolongado (>42 sem)')
												$this->salida.="			<td align=\"center\" $estilo> </td>";
											else	
												$this->salida.="			<td align=\"center\" $estilo><input type=\"checkbox\" name=\"$nombre1\" value=\"".$riesgos_bp[$i][puntaje]."".$riesgos_bp[$i][riesgo_id]."\"></td>";
										}
										else
											$this->salida.="			<td align=\"center\" $estilo>&nbsp;</td>";
									}
									$ban=1;
									break;
								}
							}
							if($ban==0 AND $semana_gestante>=$r_ini[$j] and $semana_gestante<=$r_fin[$j])
							{
								$ban1=0;
								for($s=0;$s<sizeof($registro_riesgo);$s++)
								{
									if($riesgos_bp[$i][riesgo_id]==$registro_riesgo[$a][riesgo_id] and ($registro_riesgo[$s][semana]>=$r_ini[$j] and $registro_riesgo[$s][semana]<=$r_fin[$j]))
									{
										if($registro_riesgo[$s][evolucion_id]==$evolucion AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
										{
											$this->salida.="			<td align=\"center\" $estilo><label class=\"label_error\">".$registro_riesgo[$s][valor]."</label> <input type=\"checkbox\" name=\"$nombre1\" value=\"".$riesgos_bp[$i][puntaje]."".$riesgos_bp[$i][riesgo_id]."\" checked></td>";
										}
										else
										{
											$this->salida.="			<td align=\"center\" $estilo><label class=\"label_error\">".$registro_riesgo[$s][valor]."</label> <img src=\"".GetThemePath()."/images/checksi.png\"></td>";
										}
										$ban1=1;
										break;	
									}
								}
								
								if($ban1==0)
								{
									$flag=0;
									for($s=0;$s<sizeof($registro_riesgo);$s++)
									{
										if($registro_riesgo[$a][evolucion_id]!=$evolucion AND ($registro_riesgo[$s][semana]>=$r_ini[$j] AND $registro_riesgo[$s][semana]<=$r_fin[$j]))
										{
											$flag=1;
											break;
										}
									}
									
									if($flag==0 AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
									{
										if(strtolower($riesgos_bp[$i][descripcion])=='embarazo prolongado (>42 sem)')
											$this->salida.="			<td align=\"center\" $estilo> </td>";
										else	
											$this->salida.="			<td align=\"center\" $estilo><input type=\"checkbox\" name=\"$nombre1\" value=\"".$riesgos_bp[$i][puntaje]."".$riesgos_bp[$i][riesgo_id]."\"></td>";
									}
									else
										$this->salida.="			<td align=\"center\" $estilo>&nbsp;</td>";
								}
							}
						}
						else
						{
							$gp=null;
							for($m=0;$m<sizeof($grupo_riesgo);$m++)
							{
								if($riesgos_bp[$i][grupo_id]==$grupo_riesgo[$m][grupo_id])
								{
									$gp[]=$grupo_riesgo[$m];
								}
							}
							
							$ban=0;
							for($a=0;$a<sizeof($registro_riesgo);$a++)
							{
								if($registro_riesgo[$a][semana]>=$r_ini[$j] and $registro_riesgo[$a][semana]<=$r_fin[$j])
								{
									$this->salida.="<td align=\"center\" $estilo>";
									$ban1=0;
									for($s=0;$s<sizeof($registro_riesgo);$s++)
									{
										if($riesgos_bp[$i][riesgo_id]==$registro_riesgo[$s][riesgo_id] and ($registro_riesgo[$s][semana]>=$r_ini[$j] and $registro_riesgo[$s][semana]<=$r_fin[$j]))
										{
											if($registro_riesgo[$s][evolucion_id]==$evolucion and ($semana_gestante>=$r_ini[$j] and $semana_gestante<=$r_fin[$j]) AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
											{
												$this->salida.="					<select class=\"select\" name=\"$nombre1\">";
												for($m=0;$m<sizeof($gp);$m++)
												{
													if($riesgos_bp[$i][grupo_id]==$gp[$m][grupo_id] and $registro_riesgo[$s][valor]==$gp[$m][puntaje])
													{
														$this->salida.="					<option value=\"".$gp[$m][puntaje]."".$riesgos_bp[$i][riesgo_id]."\" selected>".$gp[$m][descripcion_valor]."</option>";	
													}
													else
													{
														$this->salida.="					<option value=\"".$gp[$m][puntaje]."".$riesgos_bp[$i][riesgo_id]."\">".$gp[$m][descripcion_valor]."</option>";	
													}
												}
												$this->salida.="</select>";
											}
											else
											{
												for($m=0;$m<sizeof($gp);$m++)
												{
													if($riesgos_bp[$i][grupo_id]==$gp[$m][grupo_id] and $registro_riesgo[$s][valor]==$gp[$m][puntaje])
													{
														if($gp[$m][grupo_id]==1)
														{
															if($gp[$m][puntaje]!=1)
																$this->salida.="					<label class=\"label\">".$gp[$m][descripcion_valor]."</label>";
															else
																$this->salida.="					<label class=\"label_error\">".$gp[$m][descripcion_valor]."</label>";
														}
														else
														{
															if($gp[$m][puntaje]!=2)
																$this->salida.="					<label class=\"label\">".$gp[$m][descripcion_valor]."</label>";
															else
																$this->salida.="					<label class=\"label_error\">".$gp[$m][descripcion_valor]."</label>";
														}
													}
												}
											}
											$ban1=1;
											break;
										}
									}
											
									if($ban1==0)
									{
										$flag=0;
										for($s=0;$s<sizeof($registro_riesgo);$s++)
										{
											if($riesgos_bp[$i][riesgo_id]==$registro_riesgo[$s][riesgo_id] and ($registro_riesgo[$s][semana]>=$r_ini[$j] and $registro_riesgo[$s][semana]<=$r_fin[$j]))
											{
												if($registro_riesgo[$s][evolucion_id]==$evolucion and ($semana_gestante>=$r_ini[$j] and $semana_gestante<=$r_fin[$j]) AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
												{
													$this->salida.="					<select class=\"select\" name=\"$nombre1\">";
													for($m=0;$m<sizeof($gp);$m++)
														if($gp[$m][puntaje]==0)
															$this->salida.="						<option value=\"".$gp[$m][puntaje]."".$riesgos_bp[$i][riesgo_id]."\" selected>".$gp[$m][descripcion_valor]."</option>";
														else
																$this->salida.="						<option value=\"".$gp[$m][puntaje]."".$riesgos_bp[$i][riesgo_id]."\">".$gp[$m][descripcion_valor]."</option>";
														$this->salida.="					</select>";
												}
												else
												{
													$this->salida.="			<td align=\"center\">&nbsp;</td>";
												}
												$flag=1;
												break;
											}
										}
										if($flag==0)
										{
											if($semana_gestante>=$r_ini[$j] and $semana_gestante<=$r_fin[$j] AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
											{
												$this->salida.="					<select class=\"select\" name=\"$nombre1\">";
												for($m=0;$m<sizeof($gp);$m++)
													if($gp[$m][puntaje]==0)
														$this->salida.="						<option value=\"".$gp[$m][puntaje]."".$riesgos_bp[$i][riesgo_id]."\" selected>".$gp[$m][descripcion_valor]."</option>";
													else
														$this->salida.="						<option value=\"".$gp[$m][puntaje]."".$riesgos_bp[$i][riesgo_id]."\">".$gp[$m][descripcion_valor]."</option>";
												$this->salida.="					</select>";
											}
											else
											{
												$this->salida.="					<label class=\"label\">&nbsp;</label>";
											}
										}
									}
									$this->salida.="				</td>";
									
									$ban=1;
									break;
								}
							}
							if($ban==0 AND $semana_gestante>=$r_ini[$j] and $semana_gestante<=$r_fin[$j])
							{
								$this->salida.="<td align=\"center\" $estilo>";
								$ban1=0;
								for($s=0;$s<sizeof($registro_riesgo);$s++)
								{
									if($riesgos_bp[$i][riesgo_id]==$registro_riesgo[$s][riesgo_id] and ($registro_riesgo[$s][semana]>=$r_ini[$j] and $registro_riesgo[$s][semana]<=$r_fin[$j]))
									{
										if($registro_riesgo[$s][evolucion_id]==$evolucion and ($semana_gestante>=$r_ini[$j] and $semana_gestante<=$r_fin[$j]) AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
										{
											$this->salida.="					<select class=\"select\" name=\"$nombre1\">";
											for($m=0;$m<sizeof($gp);$m++)
											{
												if($riesgos_bp[$i][grupo_id]==$gp[$m][grupo_id] and $registro_riesgo[$s][valor]==$gp[$m][puntaje])
												{
													$this->salida.="					<option value=\"".$gp[$m][puntaje]."".$riesgos_bp[$i][riesgo_id]."\" selected>".$gp[$m][descripcion_valor]."</option>";	
												}
												else
												{
													$this->salida.="					<option value=\"".$gp[$m][puntaje]."".$riesgos_bp[$i][riesgo_id]."\">".$gp[$m][descripcion_valor]."</option>";	
												}
											}
											$this->salida.="</select>";
										}
										else
										{
											for($m=0;$m<sizeof($gp);$m++)
											{
												if($riesgos_bp[$i][grupo_id]==$gp[$m][grupo_id] and $registro_riesgo[$s][valor]==$gp[$m][puntaje])
												{
													if($gp[$m][grupo_id]==1)
													{
														if($gp[$m][puntaje]!=1)
															$this->salida.="					<label class=\"label\">".$gp[$m][descripcion_valor]."</label>";
														else
															$this->salida.="					<label class=\"label_error\">".$gp[$m][descripcion_valor]."</label>";
													}
													else
													{
														if($gp[$m][puntaje]!=2)
															$this->salida.="					<label class=\"label\">".$gp[$m][descripcion_valor]."</label>";
														else
															$this->salida.="					<label class=\"label_error\">".$gp[$m][descripcion_valor]."</label>";
													}
												}
											}
										}
										$ban1=1;
										break;
									}
								}
										
								if($ban1==0)
								{
									$flag=0;
									for($s=0;$s<sizeof($registro_riesgo);$s++)
									{
										if($riesgos_bp[$i][riesgo_id]==$registro_riesgo[$s][riesgo_id] and ($registro_riesgo[$s][semana]>=$r_ini[$j] and $registro_riesgo[$s][semana]<=$r_fin[$j]))
										{
											if($registro_riesgo[$s][evolucion_id]==$evolucion and ($semana_gestante>=$r_ini[$j] and $semana_gestante<=$r_fin[$j]) AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
											{
												$this->salida.="					<select class=\"select\" name=\"$nombre1\">";
												for($m=0;$m<sizeof($gp);$m++)
													if($gp[$m][puntaje]==0)
														$this->salida.="						<option value=\"".$gp[$m][puntaje]."".$riesgos_bp[$i][riesgo_id]."\" selected>".$gp[$m][descripcion_valor]."</option>";
													else
															$this->salida.="						<option value=\"".$gp[$m][puntaje]."".$riesgos_bp[$i][riesgo_id]."\">".$gp[$m][descripcion_valor]."</option>";
													$this->salida.="					</select>";
											}
											else
											{
												$this->salida.="			<td align=\"center\">&nbsp;</td>";
											}
											$flag=1;
											break;
										}
									}
									if($flag==0)
									{
										if($semana_gestante>=$r_ini[$j] and $semana_gestante<=$r_fin[$j] AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
										{
											$this->salida.="					<select class=\"select\" name=\"$nombre1\">";
											for($m=0;$m<sizeof($gp);$m++)
												if($gp[$m][puntaje]==0)
													$this->salida.="						<option value=\"".$gp[$m][puntaje]."".$riesgos_bp[$i][riesgo_id]."\" selected>".$gp[$m][descripcion_valor]."</option>";
												else
													$this->salida.="						<option value=\"".$gp[$m][puntaje]."".$riesgos_bp[$i][riesgo_id]."\">".$gp[$m][descripcion_valor]."</option>";
											$this->salida.="					</select>";
										}
										else
										{
											$this->salida.="					<label class=\"label\">&nbsp;</label>";
										}
									}
								}
								$this->salida.="				</td>";
							}
						}
					}
					$this->salida.="			</tr>";
				}
				$k++;
			}
			
			$this->salida.="			<tr class=\"modulo_table_list_title\" width=\"100%\">";
			$this->salida.="				<td align=\"right\" colspan=\"2\"><label>PUNTAJE ASOCIADO</label></td>";

			for($i=0;$i<3;$i++)
			{
				$ban=0;
				for($a=0;$a<sizeof($registro_riesgo);$a++)
				{
					if($registro_riesgo[$a][semana]>=$r_ini[$i] and $registro_riesgo[$a][semana]<=$r_fin[$i])
					{
						$_SESSION['puntaje'][$i]=$puntaje[$i];
						$this->salida.="		<td align=\"center\" width=\"10%\">".$puntaje[$i]."</td>";
						$ban=1;
						break;
					}
				}
				if($ban==0 AND $semana_gestante>=$r_ini[$i] and $semana_gestante<=$r_fin[$i])
				{
					$_SESSION['puntaje'][$i]=$puntaje[$i];
					$this->salida.="		<td align=\"center\" width=\"10%\">".$puntaje[$i]."</td>";
				}
			}
			$this->salida.="			</tr>";
			$this->salida.="			</table><br>";

			$this->salida.="<table align=\"center\" cellspacing=\"20\">";
			$this->salida.="	<tr>";
			if(!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn"))
				$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"guardar$pfj\" value=\"GUARDAR\"></<td>";
			$this->salida.="</form>";
			
			$accion1=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'RegistroEvolucionGestacion'));
			$accion2=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'AntecedentesGinecos'));
			
			$this->salida.="<form name=\"formasig$pfj\" action=\"$accion1\" method=\"post\">";
			$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"siguiente$pfj\" value=\"SIGUIENTE\"></<td>";
			$this->salida.="</form>";
			$this->salida.="<form name=\"formavolver$pfj\" action=\"$accion2\" method=\"post\">";
			$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"volver$pfj\" value=\"VOLVER\"></<td>";
			$this->salida.="</form>";
			$this->salida.="	</tr>";
			$this->salida.="</table>";
			
			$this->salida.=ThemeCerrarTablaSubModulo();
			
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
	}
?>