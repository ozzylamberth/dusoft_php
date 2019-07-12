<?php
	/********************************************************************************* 
 	* $Id: hc_RegistroEvolucionPFliar_RegistroEPF_HTML.class.php,v 1.3 2007/02/01 20:51:01 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_RegistroEvolucionPFliar
	* 
 	**********************************************************************************/

	class RegistroEPF_HTML
	{

		function RegistroEPF_HTML()
		{
			$this->redcolorf="#990000";
			return true;
		}
		
		function frmConsulta($vector,$registros)
		{
			$k=0;
			$ok=0;
			$salida="";
			$fecha="";
			$sino="";
			$cols=1;
			$evoluciones=array();
			$b=true;
			$b1=true;
			
			$datosPaciente=SessionGetVar("DatosPaciente");
			if($datosPaciente['sexo_id']=='M')
				$titulo='REGISTRO EVOLUCION DEL HOMBRE';
			elseif($datosPaciente['sexo_id']=='F')
				$titulo='REGISTRO EVOLUCION DE LA MUJER';
			if($datosPaciente['sexo_id']=='M')
			{
				foreach($vector as $v)
				{
					if($k%2==0)
					{
						$estilo='hc_submodulo_list_claro';
					}
					else
					{
						$estilo='hc_submodulo_list_oscuro';
					}
						
					$salida.="		<tr class=\"$estilo\">";
					$salida.="			<td $align><label class=\"".$this->SetStyle($v)."\">".$v."</label></td>";
						
					foreach($registros as $valor)
					{
						switch($k)
						{
							case 0:
								$fecha.="<td colspan=\"2\" align=\"center\" width=\"15%\">".substr($valor['fecha_registro'],0,10)."</td>";
								$sino.="<td colspan=\"2\">&nbsp;</td>";
								$evoluciones[]=$valor['evolucion_id'];
								$metodo=$valor['desc_metodo'];
								$cols=($cols*2)+1;
								if($valor['sw_otro'])
									$metodo=$valor['otro_metodo'];
									
								$salida.="	<td colspan=\"2\" align=\"center\"><label class=\"label\">".strtoupper($metodo)."</label></td>";
							break;
							case 1:
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$valor['fecha_ini']."</label></td>";
							break;
							case 2:
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$valor['fecha_fin']."</label></td>";
							break;
							case 3:
								switch($valor['autoexamen_de_testiculos'])
								{
									case 1:
										$estado="<font color=\"".$this->redcolorf."\">Si</font>";
									break;
									case 2:
										$estado="No";
									break;
								}
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
							break;
							case 4:
								switch($valor['autoexamen_de_mamas'])
								{
									case 1:
										$estado="Si";
									break;
									case 2:
										$estado="<font color=\"".$this->redcolorf."\">No</font>";
									break;
								}
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
							break;
							case 5:
								$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$valor[nombre]."</td>";
							break;
							case 6:
								$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$valor[descripcion]."</td>";
							break;
						}
					}
					$salida.="		</tr>";
					$k++;
				}
				$this->salida.="<br><table align=\"center\" border=\"1\"  class=\"hc_table_submodulo_list\" width=\"100%\">";
				$this->salida.="		<tr class=\"modulo_table_list_title\">";
				$this->salida.="			<td width=\"90%\">$titulo</td>";
				$this->salida.="		$sino";
				$this->salida.="		</tr>";
				$this->salida.="		<tr class=\"hc_table_submodulo_list_title\">";
				$this->salida.="			<td align=\"left\"><label class=\"label\">Fecha</label></td>";
				$this->salida.="		".$fecha;
				$this->salida.="		</tr>";
				$this->salida.="".	$salida;
				$this->salida.="	</tr>";
				$this->salida.="</table>";
			}
			elseif($datosPaciente['sexo_id']=='F')
			{
				$ev=0;
				foreach($vector as $key=>$valor)
				{
					if($k%2==0)
					{
						$estilo='hc_submodulo_list_claro';
					}
					else
					{
						$estilo='hc_submodulo_list_oscuro';
					}
						
					if($key!="P")
					{
						$salida.="		<tr class=\"$estilo\">";
						
						foreach($registros as $reg)
						{
							if($reg['evolucion_id']==$evolucion)
								$ok=1;
							
							if($b1)
							{
								$ev=$reg['evolucion_id'];
								$b1=false;
							}
								
							switch($valor[0])
							{
								case "Titulo":
									if($reg['evolucion_id'] == $ev)
										$salida.="			<td colspan=\"$cols\" align=\"center\" class=\"modulo_table_list_title\">$key</td>";
								break;
								
								case "SubTitulo":
										if($reg['evolucion_id'] == $ev)
											$salida.="			<td colspan=\"$cols\" align=\"center\" class=\"hc_table_submodulo_list_title\">$key</td>";
								break;
								
								case "Combo";
									if($reg['evolucion_id']==$ev)
									{
										if($valor[1]!=3)
											$salida.="			<td align=\"left\" class=\"".$this->SetStyle("C$k")."\">$key</td>";
									}
										
									$nombre=strtolower(str_replace(" ","_",$key));
									switch($valor[1])
									{
										case 1:
											$cols=($cols*2)+1;
											$evoluciones[]=$reg['evolucion_id'];	
											$metodo=$reg['desc_metodo'];
											if($reg['sw_otro'])
												$metodo=$reg['otro_metodo'];
											
											$salida.="	<td colspan=\"2\" align=\"center\"><label class=\"label\">".strtoupper($metodo)."</label></td>";
										break;
										
										case 2:
											$metodo=$reg['desc_cual'];
											if($reg['sw_cual_otro'])
												$metodo=$reg['cual_otro_metodo'];
											
											if(!$metodo)
												$metodo="N.A";	
											$salida.="	<td colspan=\"2\" align=\"center\"><label class=\"label\">".strtoupper($metodo)."</label></td>";
								
										break;
										
										case 3:
											$sel1="";$sel2="";
											if($reg['evolucion_id']==$ev)
												$salida.="			<td align=\"left\" class=\"".$this->SetStyle("C$k")."\">".substr($key,0,sizeof($key)-7)."</td>";
											$mama=substr(strtolower($nombre),0,sizeof($nombre)-7);
											
											switch($valor[2])
											{
												case 1:
													if($reg['mama_izq_'.$mama]==1)
														$estado="NORMAL";
													else
														$estado="<font color=\"".$this->redcolorf."\">ANORMAL</font>";
												break;
												
												case 2:
													if($reg['mama_der_'.$mama]==1)
													$estado="NORMAL";
												else
													$estado="<font color=\"".$this->redcolorf."\">ANORMAL</font>";
												break;
											}
											$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">$estado</label></td>";
										break;
										
										case 4:
											$sel1="";$sel2="";
											$estado="No";
											switch($reg['motivo_cierre_caso'])
											{
												case 1:
														$estado="NO FIGURA EN BD";
												break;
												
												case 2:
													$estado="MENOPAUSIA";
												break;
												
												case 3:
													$estado="CAMBIO METODO";
												break;
												
												case 4:
													$estado="COMPAÑERO CON ANTICONCEPCION";
												break;
											}
											$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">$estado</label></td>";
										break;
									}
								break;
								
								case "TextFecha":
									if($reg['evolucion_id']==$ev)
										$salida.="			<td align=\"left\" class=\"".$this->SetStyle("C$k")."\">$key</td>";

									$nombre=strtolower(str_replace(" ","_",$key));
									switch($valor[1])
									{
										case 1:
											$estado=$reg['fecha_ultima_mestruacion'];
										break;
										case 2:
											$estado=$reg['fecha_ini'];
										break;
										case 3:
											$estado=$reg['fecha_fin'];
										break;
									}
									
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">$estado</label></td>";
									
								break;
								
								case "Radio":
									if($reg['evolucion_id']==$ev)
										$salida.="			<td align=\"left\" class=\"".$this->SetStyle("C$k")."\">$key</td>";
									
									switch($valor[1])
									{
										case 1:
											if($reg['autoexamen_de_mamas']=='1')
												$estado="Si";
											else
												$estado="<font color=\"".$this->redcolorf."\">No</font>";
										break;
										case 2:
											if($reg['mareos']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 3:
											if($reg['cefalea']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 4:
											if($reg['manchas_piel']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 5:
											if($reg['acne']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 6:
											if($reg['nauzeas']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 7:
											if($reg['dolor_mamas']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 8:
											if($reg['dolor_pelvico']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 9:
											if($reg['explulsion_dispositivo']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 10:
											if($reg['tratamiento_propio_leuconea']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 11:
											if($reg['tratamiento_pareja_leuconea']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 12:
											if($reg['sintomas_urinarios']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 13:
											if($reg['hemorragia']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 14:
											if($reg['varices']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 15:
											if($reg['edema']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 16:
											if($reg['cambios_comportamiento']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 17:
											if($reg['satisfaccion_metodo']=='1')
												$estado="Si";
											else
												$estado="<font color=\"".$this->redcolorf."\">No</font>";
										break;
										case 18:
											if($reg['cambiar_metodo']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 19:
											if($reg['cierre_caso']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
									}
									
									$salida.="	<td align=\"center\" colspan=\"2\"><label class=\"label\">$estado</label></td>";
								break;
		
								case "2Text":
									$fecha.="<td colspan=\"2\" align=\"center\" width=\"15%\">".date("Y-m-d")."</td>";
									$sino.="<td width=\"5%\">Si</td>";
									$sino.="<td width=\"5%\">No</td>";
									$evoluciones[]=$reg['evolucion_id'];
									if($reg['evolucion_id']==$ev)
										$salida.="			<td align=\"left\" class=\"".$this->SetStyle("C$k")."\">$key</td>";

									$ta_alta=$reg['taalta'];
									$ta_baja=$reg['tabaja'];
									
									$style="";
									if(!empty($ta_alta) AND $ta_alta>139)
										$style="style=\"color:#990000;font-weight : bold; \"";	
									$style1="";
									if(!empty($ta_baja) AND $ta_baja<55)
										$style1="style=\"color:#990000;font-weight : bold; \"";
									
									$salida.="	<td align=\"center\" colspan=\"2\"><label class=\"label\" $style>".$ta_alta."</label> / <label class=\"label\" $style1>".$ta_baja."</label></td>";
									
								break;
								
								case "Label":
									if($reg['evolucion_id']==$ev)
										$salida.="			<td align=\"left\" class=\"label\">$key</td>";

									switch($valor[1])
									{
										case 1;
											$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$reg['nombre']."</td>";
										break;
										
										case 2:
											$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$reg['descripcion']."</td>";
										break;
									}
								break;
							}
						}
						$salida.="<tr>";
					}
				}
			}
			
			$this->salida.="<br><table align=\"center\" border=\"1\"  class=\"hc_table_submodulo_list\" width=\"100%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td width=\"90%\">$titulo</td>";
			$this->salida.="		$sino";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="			<td align=\"left\"><label class=\"label\">Fecha</label></td>";
			$this->salida.="		".$fecha;
			$this->salida.="		</tr>";
			$this->salida.="".	$salida;
			$this->salida.="	</tr>";
			$this->salida.="</table>";
			
			return $this->salida;
		}
		
		function frmHistoria($vector,$registros)
		{
			$k=0;
			$ok=0;
			$salida="";
			$fecha="";
			$sino="";
			$cols=1;
			$evoluciones=array();
			$b=true;
			$b1=true;
			
			$datosPaciente=SessionGetVar("DatosPaciente");
			if($datosPaciente['sexo_id']=='M')
				$titulo='REGISTRO EVOLUCION DEL HOMBRE';
			elseif($datosPaciente['sexo_id']=='F')
				$titulo='REGISTRO EVOLUCION DE LA MUJER';
			if($datosPaciente['sexo_id']=='M')
			{
				foreach($vector as $v)
				{
					if($k%2==0)
					{
						$estilo='hc_submodulo_list_claro';
					}
					else
					{
						$estilo='hc_submodulo_list_oscuro';
					}
						
					$salida.="		<tr class=\"$estilo\">";
					$salida.="			<td $align><label class=\"".$this->SetStyle($v)."\">".$v."</label></td>";
						
					foreach($registros as $valor)
					{
						switch($k)
						{
							case 0:
								$fecha.="<td colspan=\"2\" align=\"center\" width=\"15%\">".substr($valor['fecha_registro'],0,10)."</td>";
								$sino.="<td colspan=\"2\">&nbsp;</td>";
								$evoluciones[]=$valor['evolucion_id'];
								$metodo=$valor['desc_metodo'];
								$cols=($cols*2)+1;
								if($valor['sw_otro'])
									$metodo=$valor['otro_metodo'];
									
								$salida.="	<td colspan=\"2\" align=\"center\"><label class=\"label\">".strtoupper($metodo)."</label></td>";
							break;
							case 1:
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$valor['fecha_ini']."</label></td>";
							break;
							case 2:
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$valor['fecha_fin']."</label></td>";
							break;
							case 3:
								switch($valor['autoexamen_de_testiculos'])
								{
									case 1:
										$estado="Si";
									break;
									case 2:
										$estado="<font color=\"".$this->redcolorf."\">No</font>";
									break;
								}
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
							break;
							case 4:
								switch($valor['autoexamen_de_mamas'])
								{
									case 1:
										$estado="Si";
									break;
									case 2:
										$estado="<font color=\"".$this->redcolorf."\">No</font>";
									break;
								}
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
							break;
							case 5:
								$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$valor[nombre]."</td>";
							break;
							case 6:
								$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$valor[descripcion]."</td>";
							break;
						}
					}
					$salida.="		</tr>";
					$k++;
				}
			}
			elseif($datosPaciente['sexo_id']=='F')
			{
				$ev=0;
				foreach($vector as $key=>$valor)
				{
					if($k%2==0)
					{
						$estilo='hc_submodulo_list_claro';
					}
					else
					{
						$estilo='hc_submodulo_list_oscuro';
					}
						
					if($key!="P")
					{
						$salida.="		<tr class=\"$estilo\">";
						
						foreach($registros as $reg)
						{
							if($reg['evolucion_id']==$evolucion)
								$ok=1;
							
							if($b1)
							{
								$ev=$reg['evolucion_id'];
								$b1=false;
							}
								
							switch($valor[0])
							{
								case "Titulo":
									if($reg['evolucion_id'] == $ev)
										$salida.="			<td colspan=\"$cols\" align=\"center\" class=\"modulo_table_list_title\">$key</td>";
								break;
								
								case "SubTitulo":
										if($reg['evolucion_id'] == $ev)
											$salida.="			<td colspan=\"$cols\" align=\"center\" class=\"hc_table_submodulo_list_title\">$key</td>";
								break;
								
								case "Combo";
									if($reg['evolucion_id']==$ev)
									{
										if($valor[1]!=3)
											$salida.="			<td align=\"left\" class=\"".$this->SetStyle("C$k")."\">$key</td>";
									}
										
									$nombre=strtolower(str_replace(" ","_",$key));
									switch($valor[1])
									{
										case 1:
											$cols=($cols*2)+1;
											$evoluciones[]=$reg['evolucion_id'];	
											$metodo=$reg['desc_metodo'];
											if($reg['sw_otro'])
												$metodo=$reg['otro_metodo'];
											
											$salida.="	<td colspan=\"2\" align=\"center\"><label class=\"label\">".strtoupper($metodo)."</label></td>";
										break;
										
										case 2:
											$metodo=$reg['desc_cual'];
											if($reg['sw_cual_otro'])
												$metodo=$reg['cual_otro_metodo'];
											
											if(!$metodo)
												$metodo="N.A";	
											$salida.="	<td colspan=\"2\" align=\"center\"><label class=\"label\">".strtoupper($metodo)."</label></td>";
								
										break;
										
										case 3:
											$sel1="";$sel2="";
											if($reg['evolucion_id']==$ev)
												$salida.="			<td align=\"left\" class=\"".$this->SetStyle("C$k")."\">".substr($key,0,sizeof($key)-7)."</td>";
											$mama=substr(strtolower($nombre),0,sizeof($nombre)-7);
											
											switch($valor[2])
											{
												case 1:
													if($reg['mama_izq_'.$mama]==1)
														$estado="NORMAL";
													else
														$estado="<font color=\"".$this->redcolorf."\">ANORMAL</font>";
												break;
												
												case 2:
													if($reg['mama_der_'.$mama]==1)
													$estado="NORMAL";
												else
													$estado="<font color=\"".$this->redcolorf."\">ANORMAL</font>";
												break;
											}
											$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">$estado</label></td>";
										break;
										
										case 4:
											$sel1="";$sel2="";
											$estado="No";
											switch($reg['cierre_caso'])
											{
												case 1:
														$estado="NO FIGURA EN BD";
												break;
												
												case 2:
													$estado="MENOPAUSIA";
												break;
												
												case 3:
													$estado="CAMBIO METODO";
												break;
												
												case 4:
													$estado="COMPAÑERO CON ANTICONCEPCION";
												break;
											}
											$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">$estado</label></td>";
										break;
									}
								break;
								
								case "TextFecha":
									if($reg['evolucion_id']==$ev)
										$salida.="			<td align=\"left\" class=\"".$this->SetStyle("C$k")."\">$key</td>";

									$nombre=strtolower(str_replace(" ","_",$key));
									switch($valor[1])
									{
										case 1:
											$estado=$reg['fecha_ultima_mestruacion'];
										break;
										case 2:
											$estado=$reg['fecha_ini'];
										break;
										case 3:
											$estado=$reg['fecha_fin'];
										break;
									}
									
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">$estado</label></td>";
									
								break;
								
								case "Radio":
									if($reg['evolucion_id']==$ev)
										$salida.="			<td align=\"left\" class=\"".$this->SetStyle("C$k")."\">$key</td>";
									
									switch($valor[1])
									{
										case 1:
											if($reg['autoexamen_de_mamas']=='1')
												$estado="Si";
											else
												$estado="<font color=\"".$this->redcolorf."\">No</font>";
										break;
										case 2:
											if($reg['mareos']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 3:
											if($reg['cefalea']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 4:
											if($reg['manchas_piel']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 5:
											if($reg['acne']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 6:
											if($reg['nauzeas']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 7:
											if($reg['dolor_mamas']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 8:
											if($reg['dolor_pelvico']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 9:
											if($reg['explulsion_dispositivo']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 10:
											if($reg['tratamiento_propio_leuconea']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 11:
											if($reg['tratamiento_pareja_leuconea']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 12:
											if($reg['sintomas_urinarios']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 13:
											if($reg['hemorragia']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 14:
											if($reg['varices']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 15:
											if($reg['edema']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 16:
											if($reg['cambios_comportamiento']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 17:
											if($reg['satisfaccion_metodo']=='1')
												$estado="Si";
											else
												$estado="<font color=\"".$this->redcolorf."\">No</font>";
										break;
										case 18:
											if($reg['cambiar_metodo']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 19:
											if($reg['cierre_caso']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
									}
									
									$salida.="	<td align=\"center\" colspan=\"2\"><label class=\"label\">$estado</label></td>";
								break;
		
								case "2Text":
									$fecha.="<td colspan=\"2\" align=\"center\" width=\"15%\">".date("Y-m-d")."</td>";
									$sino.="<td width=\"5%\">Si</td>";
									$sino.="<td width=\"5%\">No</td>";
									$evoluciones[]=$reg['evolucion_id'];
									if($reg['evolucion_id']==$ev)
										$salida.="			<td align=\"left\" class=\"".$this->SetStyle("C$k")."\">$key</td>";

									$ta_alta=$reg['taalta'];
									$ta_baja=$reg['tabaja'];
									
									$style="";
									if(!empty($ta_alta) AND $ta_alta>139)
										$style="style=\"color:#990000;font-weight : bold; \"";	
									$style1="";
									if(!empty($ta_baja) AND $ta_baja<55)
										$style1="style=\"color:#990000;font-weight : bold; \"";
									
									$salida.="	<td align=\"center\" colspan=\"2\"><label class=\"label\" $style>".$ta_alta."</label> / <label class=\"label\" $style1>".$ta_baja."</label></td>";
									
								break;
								
								case "Label":
									if($reg['evolucion_id']==$ev)
										$salida.="			<td align=\"left\" class=\"label\">$key</td>";

									switch($valor[1])
									{
										case 1;
											$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$reg['nombre']."</td>";
										break;
										
										case 2:
											$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$reg['descripcion']."</td>";
										break;
									}
								break;
							}
						}
						$salida.="<tr>";
					}
				}
			}
			
			$this->salida.="<br><table align=\"center\" border=\"1\"  class=\"hc_table_submodulo_list\" width=\"100%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td width=\"90%\">$titulo</td>";
			$this->salida.="		$sino";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="			<td align=\"left\"><label class=\"label\">Fecha</label></td>";
			$this->salida.="		".$fecha;
			$this->salida.="		</tr>";
			$this->salida.="".	$salida;
			$this->salida.="	</tr>";
			$this->salida.="</table>";
			
			return $this->salida;
		}
		
		function frmForma($vector,$registros,$metodos,$datosprofesional,$signos,$pruebas,$Laboratorios,$resultadosLab)
		{
			$pfj=SessionGetvar("Prefijo");
			$evolucion=SessionGetvar("Evolucion");
			$paso=SessionGetvar("Paso");
			$datosPaciente=SessionGetVar("DatosPaciente");
			
			if($datosPaciente['sexo_id']=='M')
				$titulo='REGISTRO EVOLUCION DEL HOMBRE';
			elseif($datosPaciente['sexo_id']=='F')
				$titulo='REGISTRO EVOLUCION DE LA MUJER';
			
			$this->salida.= ThemeAbrirTablaSubModulo($titulo);
			
			$this->salida.= "      <table border=\"0\" width=\"100%\" align=\"center\">";
			$this->salida.= $this->SetStyle("MensajeError");
			$this->salida.= "      </table><br>";
			
			$accion=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'RegistroEvolucionPFliar'));
			
			$this->salida.="<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
			
			$k=0;
			$ok=0;
			$salida="";
			$fecha="";
			$sino="";
			$cols=1;
			$evoluciones=array();
			$b=true;
			$b1=true;

			if($datosPaciente['sexo_id']=='M')
			{
				foreach($vector as $v)
				{
					if($k%2==0)
					{
						$estilo='hc_submodulo_list_claro';
					}
					else
					{
						$estilo='hc_submodulo_list_oscuro';
					}
						
					$salida.="		<tr class=\"$estilo\">";
					$salida.="			<td $align><label class=\"".$this->SetStyle($v)."\">".$v."</label></td>";
						
					foreach($registros as $valor)
					{
						if($valor['evolucion_id']==$evolucion)
							$ok=1;
							
						switch($k)
						{
							case 0:
								$fecha.="<td colspan=\"2\" align=\"center\" width=\"15%\">".substr($valor['fecha_registro'],0,10)."</td>";
								$sino.="<td colspan=\"2\">&nbsp;</td>";
								$evoluciones[]=$valor['evolucion_id'];
								$metodo=$valor['desc_metodo'];
								$cols=($cols*2)+1;
								if($valor['sw_otro'])
									$metodo=$valor['otro_metodo'];
									
								$salida.="	<td colspan=\"2\" align=\"center\"><label class=\"label\">".strtoupper($metodo)."</label></td>";
							break;
							case 1:
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$valor['fecha_ini']."</label></td>";
							break;
							case 2:
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$valor['fecha_fin']."</label></td>";
							break;
							case 3:
								switch($valor['autoexamen_de_testiculos'])
								{
									case 1:
										$estado="Si";
									break;
									case 2:
										$estado="<font color=\"".$this->redcolorf."\">No</font>";
									break;
								}
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
							break;
							case 4:
								switch($valor['autoexamen_de_mamas'])
								{
									case 1:
										$estado="Si";
									break;
									case 2:
										$estado="<font color=\"".$this->redcolorf."\">No</font>";
									break;
								}
								$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">".$estado."</label></td>";
							break;
							case 5:
								$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$valor[nombre]."</td>";
							break;
							case 6:
								$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$valor[descripcion]."</td>";
							break;
						}
					}
					if($ok==0)
					{
						switch($k)
						{
							case 0:
								$fecha.="<td colspan=\"2\" align=\"center\" width=\"15%\">".date("Y-m-d")."</td>";
								$sino.="<td width=\"5%\">Si</td>";
								$sino.="<td width=\"5%\">No</td>";
								$cols=($cols*2)+1;
								$evoluciones[]=$evolucion;
								
								$salida.="			<td colspan=\"2\" align=\"center\">";
								$salida.="				<select name=\"metodo$pfj\" class=\"select\" onChange=\"OtroMetodo(document.formades$pfj.metodo$pfj.value)\">";
								$salida.="					<option value=\"\" $sel>--SELECCIONE--</option>";
								foreach($metodos as $m)
								{
									if($m['metodo_id']==$_REQUEST['metodo'.$pfj])
										$sel="selected";
									else
										$sel="";
									$salida.="					<option value=\"".$m['metodo_id']."\" $sel>".strtoupper($m['descripcion'])."</option>";
								}
								$salida.="				</select>";
								$display="display:none";
								if($_REQUEST['otro_metodo'.$pfj])
									$display="display:block";
								
								$salida.="				<div id=\"otro_metodo\" style=\"$display\">";
								$salida.="					<input type=\"text\" class=\"input-text\" name=\"otro_metodo$pfj\" size=\"38\" value=\"".$_REQUEST['otro_metodo'.$pfj]."\">";
								$salida.="				</div>";
								$salida.="			</td>";
							break;
							case 1:
								$salida.="			<td colspan=\"2\" align=\"center\"><input type=\"text\" class=\"input-text\" name=\"fecha_ini$pfj\" maxlength=\"10\" size=\"10\" value=\"".$_REQUEST['fecha_ini'.$pfj]."\"><sub>".ReturnOpenCalendario("formades$pfj","fecha_ini$pfj","-")."</sub></td>";
							break;
							case 2:
								$salida.="			<td colspan=\"2\" align=\"center\"><input type=\"text\" class=\"input-text\" name=\"fecha_fin$pfj\" maxlength=\"10\" size=\"10\" value=\"".$_REQUEST['fecha_fin'.$pfj]."\"><sub>".ReturnOpenCalendario("formades$pfj","fecha_fin$pfj","-")."</sub></td>";
							break;
							case 3:
								$check1="";$check2="";
								if(!empty($_REQUEST['testiculos'.$pfj]))
								{
									if($_REQUEST['testiculos'.$pfj]=='1')
										$check1="checked";
									else
										$check2="checked";
								}
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"testiculos$pfj\" value=\"1\" $check1></td>";
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"testiculos$pfj\" value=\"2\" $check2></td>";
							break;
							case 4:
								$check1="";$check2="";
								if(!empty($_REQUEST['mamas'.$pfj]))
								{
									if($_REQUEST['mamas'.$pfj]=='1')
										$check1="checked";
									else
										$check2="checked";
								}
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"mamas$pfj\" value=\"1\" $check1></td>";
								$salida.="	<td align=\"center\"><input type=\"radio\" name=\"mamas$pfj\" value=\"2\" $check2></td>";
						
							break;
							case 5:
								$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$datosprofesional[0][nombre]."</td>";
							break;
							case 6:
								$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$datosprofesional[0][descripcion]."</td>";
							break;
						}
					}
					$salida.="		</tr>";
					$k++;
				}
			}
			elseif($datosPaciente['sexo_id']=='F')
			{
				$ev=0;
				foreach($vector as $key=>$valor)
				{
					if($k%2==0)
					{
						$estilo='hc_submodulo_list_claro';
					}
					else
					{
						$estilo='hc_submodulo_list_oscuro';
					}
						
					if($key!="P")
					{
						$salida.="		<tr class=\"$estilo\">";
						
						foreach($registros as $reg)
						{
							if($reg['evolucion_id']==$evolucion)
								$ok=1;
							
							if($b1)
							{
								$ev=$reg['evolucion_id'];
								$b1=false;
							}
								
							switch($valor[0])
							{
								case "Titulo":
									if($reg['evolucion_id'] == $ev)
										$salida.="			<td colspan=\"$cols\" align=\"center\" class=\"modulo_table_list_title\">$key</td>";
								break;
								
								case "SubTitulo":
										if($reg['evolucion_id'] == $ev)
											$salida.="			<td colspan=\"$cols\" align=\"center\" class=\"hc_table_submodulo_list_title\">$key</td>";
								break;
								
								case "Combo";
									if($reg['evolucion_id']==$ev)
									{
										if($valor[1]!=3)
											$salida.="			<td align=\"left\" class=\"".$this->SetStyle("C$k")."\">$key</td>";
									}
										
									$nombre=strtolower(str_replace(" ","_",$key));
									switch($valor[1])
									{
										case 1:
											$cols=($cols*2)+1;
											$evoluciones[]=$reg['evolucion_id'];	
											$metodo=$reg['desc_metodo'];
											if($reg['sw_otro'])
												$metodo=$reg['otro_metodo'];
											
											$salida.="	<td colspan=\"2\" align=\"center\"><label class=\"label\">".strtoupper($metodo)."</label></td>";
										break;
										
										case 2:
											$metodo=$reg['desc_cual'];
											if($reg['sw_cual_otro'])
												$metodo=$reg['cual_otro_metodo'];
											
											if(!$metodo)
												$metodo="N.A";	
											$salida.="	<td colspan=\"2\" align=\"center\"><label class=\"label\">".strtoupper($metodo)."</label></td>";
								
										break;
										
										case 3:
											$sel1="";$sel2="";
											if($reg['evolucion_id']==$ev)
												$salida.="			<td align=\"left\" class=\"".$this->SetStyle("C$k")."\">".substr($key,0,sizeof($key)-7)."</td>";
											$mama=substr(strtolower($nombre),0,sizeof($nombre)-7);
											
											switch($valor[2])
											{
												case 1:
													if($reg['mama_izq_'.$mama]==1)
														$estado="NORMAL";
													else
														$estado="<font color=\"".$this->redcolorf."\">ANORMAL</font>";
												break;
												
												case 2:
													if($reg['mama_der_'.$mama]==1)
													$estado="NORMAL";
												else
													$estado="<font color=\"".$this->redcolorf."\">ANORMAL</font>";
												break;
											}
											$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">$estado</label></td>";
										break;
										
										case 4:
											$sel1="";$sel2="";
											$estado="No";
											switch($reg['motivo_cierre_caso'])
											{
												case 1:
														$estado="NO FIGURA EN BD";
												break;
												
												case 2:
													$estado="MENOPAUSIA";
												break;
												
												case 3:
													$estado="CAMBIO METODO";
												break;
												
												case 4:
													$estado="COMPAÑERO CON ANTICONCEPCION";
												break;
												
												case 5:
													$estado="MEJORIA";
												break;
												
												case 6:
													$estado="CAMBIO DE IPS";
												break;
												
												case 7:
													$estado="RETIRO DE EPS";
												break;
												
												case 8:
													$estado="ALTA VOLUNTARIA";
												break;
												
												case 9:
													$estado="MUERTE";
												break;
											}
											$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">$estado</label></td>";
										break;
									}
								break;
								
								case "TextFecha":
									if($reg['evolucion_id']==$ev)
										$salida.="			<td align=\"left\" class=\"".$this->SetStyle("C$k")."\">$key</td>";
									
									$nombre=strtolower(str_replace(" ","_",$key));
									switch($valor[1])
									{
										case 1:
											$estado=$reg['fecha_ultima_mestruacion'];
										break;
										case 2:
											$estado=$reg['fecha_ini'];
										break;
										case 3:
											$estado=$reg['fecha_fin'];
										break;
									}
									
									$salida.="			<td colspan=\"2\" align=\"center\"><label class=\"label\">$estado</label></td>";
									
								break;
								
								case "Radio":
									if($reg['evolucion_id']==$ev)
										$salida.="			<td align=\"left\" class=\"".$this->SetStyle("C$k")."\">$key</td>";
									
									switch($valor[1])
									{
										case 1:
											if($reg['autoexamen_de_mamas']=='1')
												$estado="Si";
											else
												$estado="<font color=\"".$this->redcolorf."\">No</font>";
										break;
										case 2:
											if($reg['mareos']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 3:
											if($reg['cefalea']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 4:
											if($reg['manchas_piel']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 5:
											if($reg['acne']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 6:
											if($reg['nauzeas']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 7:
											if($reg['dolor_mamas']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 8:
											if($reg['dolor_pelvico']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 9:
											if($reg['explulsion_dispositivo']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 10:
											if($reg['tratamiento_propio_leuconea']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 11:
											if($reg['tratamiento_pareja_leuconea']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 12:
											if($reg['sintomas_urinarios']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 13:
											if($reg['hemorragia']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 14:
											if($reg['varices']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 15:
											if($reg['edema']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 16:
											if($reg['cambios_comportamiento']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 17:
											if($reg['satisfaccion_metodo']=='1')
												$estado="Si";
											else
												$estado="<font color=\"".$this->redcolorf."\">No</font>";
										break;
										case 18:
											if($reg['cambiar_metodo']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
										case 19:
											if($reg['cierre_caso']=='1')
												$estado="<font color=\"".$this->redcolorf."\">Si</font>";
											else
												$estado="No";
										break;
									}
									
									$salida.="	<td align=\"center\" colspan=\"2\"><label class=\"label\">$estado</label></td>";
								break;
		
								case "2Text":
									$fecha.="<td colspan=\"2\" align=\"center\" width=\"15%\">".substr($reg['fecha_registro'],0,10)."</td>";
									$sino.="<td width=\"5%\">Si</td>";
									$sino.="<td width=\"5%\">No</td>";
									$evoluciones[]=$reg['evolucion_id'];
									if($reg['evolucion_id']==$ev)
										$salida.="			<td align=\"left\" class=\"".$this->SetStyle("C$k")."\">$key</td>";

									$ta_alta=$reg['taalta'];
									$ta_baja=$reg['tabaja'];
									
									$style="";
									if(!empty($ta_alta) AND $ta_alta>139)
										$style="style=\"color:#990000;font-weight : bold; \"";	
									$style1="";
									if(!empty($ta_baja) AND $ta_baja<55)
										$style1="style=\"color:#990000;font-weight : bold; \"";
									
									$salida.="	<td align=\"center\" colspan=\"2\"><label class=\"label\" $style>".$ta_alta."</label> / <label class=\"label\" $style1>".$ta_baja."</label></td>";
									
								break;
								
								case "Label":
									if($reg['evolucion_id']==$ev)
										$salida.="			<td align=\"left\" class=\"label\">$key</td>";

									switch($valor[1])
									{
										case 1;
											$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$reg['nombre']."</td>";
										break;
										
										case 2:
											$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$reg['descripcion']."</td>";
										break;
									}
								break;
							}
						}
						
						if($ok==0)
						{
							if($b)
							{
								$cols=($cols*2)+1;
								$evoluciones[]=$evolucion;
								$b=false;
							}
							switch($valor[0])
							{
								case "Titulo":
									if(!$registros)
										$salida.="			<td colspan=\"$cols\" align=\"center\" class=\"modulo_table_list_title\">$key</td>";
								break;
								
								case "SubTitulo":
									if(!$registros)
										$salida.="			<td colspan=\"$cols\" align=\"center\" class=\"hc_table_submodulo_list_title\">$key</td>";
								break;
								
								case "Combo":
									if(!$registros)
									{
										if($valor[1]!=3)
											$salida.="			<td align=\"left\" class=\"".$this->SetStyle("C$k")."\">$key</td>";
									}
									
									$nombre=strtolower(str_replace(" ","_",$key));
									switch($valor[1])
									{
										case 1:
											$salida.="			<td colspan=\"2\" align=\"center\">";
											$salida.="				<select name=\"$nombre$pfj\" class=\"select\" onChange=\"OtroMetodo(document.formades$pfj.$nombre$pfj.value,'otro_metodo')\">";
											$salida.="					<option value=\"\" $sel>--SELECCIONE--</option>";
											foreach($metodos as $m)
											{
												if($m['metodo_id']==$_REQUEST[$nombre.$pfj])
													$sel="selected";
												else
													$sel="";
												$salida.="					<option value=\"".$m['metodo_id']."\" $sel>".strtoupper($m['descripcion'])."</option>";
											}
											$salida.="				</select>";
											$display="display:none";
											if($_REQUEST['otro_metodo'.$pfj])
												$display="display:block";
											
											$salida.="				<div id=\"otro_metodo\" style=\"$display\">";
											$salida.="					<input type=\"text\" class=\"input-text\" name=\"otro_metodo$pfj\" size=\"38\" value=\"".$_REQUEST['otro_metodo'.$pfj]."\">";
											$salida.="				</div>";
											$salida.="			</td>";
										break;
										
										case 2:
											$salida.="			<td colspan=\"2\" align=\"center\">";
											$salida.="				<select name=\"$nombre$pfj\" class=\"select\" onChange=\"OtroMetodo(document.formades$pfj.$nombre$pfj.value,'capa_cual')\">";
											$salida.="					<option value=\"\">--SELECCIONE--</option>";
											foreach($metodos as $m)
											{
												if($m['metodo_id']==$_REQUEST[$nombre.$pfj])
													$sel="selected";
												else
													$sel="";
												$salida.="					<option value=\"".$m['metodo_id']."\" $sel>".strtoupper($m['descripcion'])."</option>";
											}
											$salida.="				</select>";
											
											$display="display:none";
											if($_REQUEST['cual_otro_metodo'.$pfj])
												$display="display:block";
											
											$salida.="				<div id=\"capa_cual\" style=\"$display\">";
											$salida.="					<input type=\"text\" class=\"input-text\" name=\"cual_otro_metodo$pfj\" size=\"38\" value=\"".$_REQUEST['cual_otro_metodo'.$pfj]."\">";
											$salida.="				</div>";
											
											$salida.="			</td>";
										break;
										
										case 3:
											$sel1="";$sel2="";
											if(!$registros)
												$salida.="			<td align=\"left\" class=\"".$this->SetStyle("C$k")."\">".substr($key,0,sizeof($key)-7)."</td>";
											
											if(!empty($_REQUEST[$nombre.$pfj]))
											{
												if($_REQUEST[$nombre.$pfj]=='1')
													$sel1="selected";
												else
													$sel2="selected";
											}
											
											$salida.="			<td colspan=\"2\" align=\"center\">";
											$salida.="				<select name=\"$nombre$pfj\" class=\"select\">";
											$salida.="					<option value=\"1\" $sel1>NORMAL</option>";
											$salida.="					<option value=\"2\" $sel2>ANORMAL</option>";
											$salida.="				</select>";
											$salida.="			</td>";
										break;
										case 4:
											$sel1="";$sel2="";
											
											if(!empty($_REQUEST[$nombre.$pfj]))
											{
												switch($_REQUEST[$nombre.$pfj])
												{
													case 1:
														$sel1="selected";
													break;
													
													case 2:
														$sel2="selected";
													break;
													
													case 3:
														$sel3="selected";
													break;
													
													case 4:
														$sel4="selected";
													break;
													
													case 5:
														$sel4="selected";
													break;
													case 6:
													
														$sel4="selected";
													break;
													
													case 7:
														$sel4="selected";
													break;
													
													case 8:
														$sel4="selected";
													break;
													
													case 9:
														$sel4="selected";
													break;
												}
											}
											
											$salida.="			<td colspan=\"2\" align=\"center\">";
											$salida.="				<select name=\"$nombre$pfj\" class=\"select\">";
											$salida.="					<option value=\"\">--SELECCIONE--</option>";
											$salida.="					<option value=\"1\" $sel1>NO FIGURA EN BD</option>";
											$salida.="					<option value=\"2\" $sel2>MENOPAUSIA</option>";
											$salida.="					<option value=\"3\" $sel3>CAMBIO METODO</option>";
											$salida.="					<option value=\"4\" $sel4>COMPAÑERO CON ANTICONCEPTIVO</option>";
											$salida.="					<option value=\"5\" $sel5>MEJORIA</option>";
											$salida.="					<option value=\"6\" $sel6>CAMBIO DE IPS</option>";
											$salida.="					<option value=\"7\" $sel7>RETIRO DE EPS</option>";
											$salida.="					<option value=\"8\" $sel8>ALTA VOLUNTARIA</option>";
											$salida.="					<option value=\"9\" $sel9>MUERTE</option>";
											$salida.="				</select>";
											$salida.="			</td>";
										break;
									}
								break;
								
								case "TextFecha":
									
									if(!$registros)
										$salida.="			<td align=\"left\" class=\"".$this->SetStyle("C$k")."\">$key</td>";

									$nombre=strtolower(str_replace(" ","_",$key));

									$salida.="		<td colspan=\"2\" align=\"center\"><input type=\"text\" class=\"input-text\" name=\"$nombre$pfj\" maxlength=\"10\" size=\"10\" value=\"".$_REQUEST[$nombre.$pfj]."\"><sub>".ReturnOpenCalendario("formades$pfj",$nombre.$pfj,"-")."</sub></td>";
								break;
								
								case "Radio":
									if(!$registros)
										$salida.="			<td align=\"left\" class=\"".$this->SetStyle("C$k")."\">$key</td>";
										
									$nombre=strtolower(str_replace(" ","_",$key));
									$check1="";$check2="";
									if(!empty($_REQUEST[$nombre.$pfj]))
									{
										if($_REQUEST[$nombre.$pfj]=='1')
											$check1="checked";
										else
											$check2="checked";
									}
									
									if($valor[2])
									{
										$onclick1="onclick=\"Cambio(this.form,false,".$valor[2].")\"";
										$onclick2="onclick=\"Cambio(this.form,true,".$valor[2].")\"";
									}

									$salida.="	<td align=\"center\"><input type=\"radio\" name=\"$nombre$pfj\" value=\"1\" $check1 $onclick1></td>";
									$salida.="	<td align=\"center\"><input type=\"radio\" name=\"$nombre$pfj\" value=\"2\" $check2 $onclick2></td>";
									
								break;
								
								case "2Text":
									$fecha.="<td colspan=\"2\" align=\"center\" width=\"15%\">".date("Y-m-d")."</td>";
									$sino.="<td width=\"5%\">Si</td>";
									$sino.="<td width=\"5%\">No</td>";
									if(!$registros)
									$salida.="			<td align=\"left\" class=\"".$this->SetStyle("C$k")."\">$key</td>";
								
									if($_REQUEST['ta_alta'.$pfj])
										$ta_alta=$_REQUEST['ta_alta'.$pfj];
									else
										$ta_alta=$signos[0][ta_alta];
									
									$style="";
									if(!empty($ta_alta) AND $ta_alta>139)
										$style="style=\"color:#990000;font-weight : bold; \"";
										
									if($_REQUEST['ta_baja'.$pfj])
										$ta_baja=$_REQUEST['ta_baja'.$pfj];
									else
										$ta_baja=$signos[0][ta_baja];
										
									$style1="";
									if(!empty($ta_baja) AND $ta_baja<55)
										$style1="style=\"color:#990000;font-weight : bold; \"";
									
									$salida.="	<td align=\"center\" colspan=\"2\"><input type=\"text\" $style class=\"input-text\" name=\"ta_alta$pfj\" value=\"$ta_alta\" maxlength=\"5\" size=\"5\">";
									$salida.="	/ <input type=\"text\" class=\"input-text\" $style1 name=\"ta_baja$pfj\" value=\"$ta_baja\" maxlength=\"5\" size=\"5\"></td>";
					
								break;
								
								case "Label":
									if(!$registros)
										$salida.="			<td align=\"left\" class=\"label\">$key</td>";
									
									switch($valor[1])
									{
										case 1;
											$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$datosprofesional[0][nombre]."</td>";
										break;
										
										case 2:
											$salida.="			<td colspan=\"2\" align=\"center\" class=\"label\">".$datosprofesional[0][descripcion]."</td>";
										break;
									}
								break;
							}
						}
						$salida.="		</tr>";
					}
					else
					{
						$cols=$cols+(sizeof($registros)*2);
						$salida.="<tr class=\"modulo_table_list_title\">";
						$salida.="	<td colspan=\"$cols\">PRUEBAS DE LABORATORIO</td>";
						$salida.="</tr>";
						
						$n=0;
						foreach($pruebas as $pruebasLab)
						{
							if($n%2==0)
							{
								$estilo2='hc_submodulo_list_claro';
							}
							else
							{
								$estilo2='hc_submodulo_list_oscuro';
							}
							
							if(empty($pruebasLab['alias']))
								$descripcion=$pruebasLab['descripcion'];
							else
								$descripcion=$pruebasLab['alias'];
							
							$salida.="		<tr class=\"$estilo2\">";
							$salida.="			<td><label class=\"label\">".$descripcion."</label></td>";
							
							$r=0;
							while($r<sizeof($evoluciones))
							{
								$a=0;
								foreach($resultadosLab as $resultados)
								{
									if($pruebasLab['cargo_cups']==$resultados['cargo'] AND $resultados['evolucion_id']==$evoluciones[$r])
									{
										$sw_modo=$resultados['sw_modo_resultado'];
										$resultado_id=$resultados['resultado_id'];
										$salida.="<td align=\"center\" colspan=\"2\">";
										$datos="resultado_id=".$resultado_id."&sw_modo=".$sw_modo;
										$url="classes/Visualizar/Visualizar.class.php?".$datos;
										if($resultados['sw_alerta'])
											$salida.="	<a href=\"javascript:AbrirVentanaVer('$url')\"><b>Ver</b></a>";
										else
											$salida.="	<a href=\"javascript:AbrirVentanaVer('$url')\">Ver</a>";
										$salida.="</td>";
										$a=1;
										break;
									}
								}
								if($a==0)
								{
									$b=0;
								
									foreach($Laboratorios as $lab)
									{
										if($pruebasLab['cargo_cups']==$lab['cargo'] AND $lab['evolucion_id']==$evoluciones[$r])
										{
											$salida.="<td align=\"center\" colspan=\"2\" id=\"trans$n$r\">";
											$datos="cargo=".$lab['cargo']."&descripcion=".$lab['descripcion']."&op=Transcribir&periodo=".($r+1)."&estilo=$estilo&evolucion_id=".$lab['evolucion_id']."&trans=trans$n$r";
											$url="classes/Transcripcion/TranscripcionExamenes.class.php?".$datos;
											$salida.="	<a href=\"javascript:AbrirVentana('$url')\">Transcribir</a>";
											$salida.="</td>";
											$b=1;
											break;
										}
									}
								}
								if($a==0 AND $b==0)
										$salida.="			<td align=\"center\" colspan=\"2\">&nbsp;</td>";
								$r++;
							}
							$salida.="</tr>";	
							$n++;
						}
					}
					$k++;
				}
			}
			$this->salida.="<table align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td width=\"90%\">$titulo</td>";
			$this->salida.="		$sino";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="			<td align=\"left\"><label class=\"label\">Fecha</label></td>";
			$this->salida.="		".$fecha;
			$this->salida.="		</tr>";
			$this->salida.="".	$salida;
			$this->salida.="	</tr>";
			$this->salida.="</table>";
			
			/*$accionA=ModuloHCGetURL($evolucion,19,0,'',false,array('accion'=>'RegistroEvolucionPFliar','paso_volver'=>$paso,'prefijo'=>$pfj));
			$accionB=ModuloHCGetURL($evolucion,18,0,'',false,array('accion'=>'RegistroEvolucionPFliar','paso_volver'=>$paso,'prefijo'=>$pfj));
			$accionC=ModuloHCGetURL($evolucion,12,0,'',false,array('accion'=>'RegistroEvolucionPFliar','paso_volver'=>$paso,'prefijo'=>$pfj));
			$accionD=ModuloHCGetURL($evolucion,13,0,'',false,array('accion'=>'RegistroEvolucionPFliar','paso_volver'=>$paso,'prefijo'=>$pfj));
			$accionE=ModuloHCGetURL($evolucion,17,0,'',false,array('accion'=>'RegistroEvolucionPFliar','paso_volver'=>$paso,'prefijo'=>$pfj));
			
			$this->salida.="<br><table align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="	<tr class=\"label\" align=\"center\">";
			$this->salida.="		<td><a href=\"$accionA\">SOLICITUD DE PROCEDIMIENTOS NO QUIRURGICOS</a></td>";
			$this->salida.="	</tr>";
			$this->salida.="	<tr class=\"label\" align=\"center\">";
			$this->salida.="		<td><a href=\"$accionB\">SOLICITUD DE PROCEDIMIENTOS QUIRURGICOS</a></td>";
			$this->salida.="	</tr>";
			$this->salida.="	<tr class=\"label\" align=\"center\">";
			$this->salida.="		<td><a href=\"$accionC\">SOLICITUD DE APOYOS DIAGNOSTICOS</a></td>";
			$this->salida.="	</tr>";
			$this->salida.="	<tr class=\"label\" align=\"center\">";
			$this->salida.="		<td><a href=\"$accionD\">SOLICITUD DE MEDICAMENTOS</a></td>";
			$this->salida.="	</tr>";
			$this->salida.="	<tr class=\"label\" align=\"center\">";
			$this->salida.="		<td><a href=\"$accionE\">SOLICITUD DE INTERCONSULTA</a></td>";
			$this->salida.="	</tr>";
			$this->salida.="</table>";*/
			
			$this->salida.="	<table border=\"0\" align=\"center\" cellspacing=\"20\">";
			$this->salida.="		<tr>";
			$this->salida.="			<td><input class=\"input-submit\" type=\"submit\" name=\"guardar$pfj\" value=\"GUARDAR\"></td>";
			$this->salida.="</form>";
			
			$accion1=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'GraficasSeguimientoPFliar'));
			if($datosPaciente['sexo_id']=='F')
				$accion2=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'AntecedentesGinecos'));
			if($datosPaciente['sexo_id']=='M')
				$accion2=ModuloHCGetURL($evolucion,-1,0,'',false);
			
			$this->salida.="<form name=\"formasig$pfj\" action=\"$accion1\" method=\"post\">";
			$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"siguiente$pfj\" value=\"SIGUIENTE\"></td>";
			$this->salida.="</form>";
			$this->salida.="<form name=\"formavolver$pfj\" action=\"$accion2\" method=\"post\">";
			$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"volver$pfj\" value=\"VOLVER\"></td>";
			$this->salida.="</form>";
			$this->salida.="	</tr>";
			$this->salida.="</table>";
			
			$this->salida.= ThemeCerrarTablaSubModulo();
			
			$this->salida .= "	<script language=\"javascript\">";
			$this->salida .= "		var cont=0;\n";
			$this->salida .= "		var valores=new Array();\n";
			$this->salida .= "		var j=0;\n";

			$this->salida .= "		function OtroMetodo(valor,capa)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			if(valor==7)\n";
			$this->salida .= "				MostrarSpan(capa);\n";
			$this->salida .= "			else\n";
			$this->salida .= "				Cerrar(capa);\n";
			$this->salida .= "		}\n";
			
			$this->salida .= "		function Cambio(forma,x,valor)";
			$this->salida .= "		{";
			$this->salida .= "			switch(valor){";
			$this->salida .= "			case 1:";
			$this->salida .= "				forma.cual$pfj.disabled=x;";
			$this->salida .= "			break;";
			$this->salida .= "			case 2:";
			$this->salida .= "				forma.motivo_cierre_de_caso$pfj.disabled=x;";
			$this->salida .= "			break;}";
			$this->salida .= "		}";
			
			$this->salida .= "		function Checkeo(nombre,x,valor)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			var i;\n";
			$this->salida .= "			switch(valor)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				case 1:\n";
			$this->salida .= "					cont=0;\n";
			$this->salida .= "					if(x)\n";
			$this->salida .= "					{\n";
			$this->salida .= "						for(i=0;i<document.formades$pfj.elements.length;i++)\n";
			$this->salida .= "						{\n";
			$this->salida .= "							if(document.formades$pfj.elements[i].type=='checkbox' && document.formades$pfj.elements[i].value!=valor)\n";
			$this->salida .= "							{\n";
			$this->salida .= "								document.formades$pfj.elements[i].disabled=true;\n";
			$this->salida .= "								document.formades$pfj.elements[i].checked=false;\n";
			$this->salida .= "							}\n";
			$this->salida .= "						}\n";
			$this->salida .= "					}\n";
			$this->salida .= "					else\n";
			$this->salida .= "					{\n";
			$this->salida .= "						for(i=0;i<document.formades$pfj.elements.length;i++)\n";
			$this->salida .= "						{\n";
			$this->salida .= "							if(document.formades$pfj.elements[i].type=='checkbox' && document.formades$pfj.elements[i].value!=valor)\n";
			$this->salida .= "								document.formades$pfj.elements[i].disabled=false;\n";
			$this->salida .= "						}\n";
			$this->salida .= "					}\n";
			$this->salida .= "				break\n";
			$this->salida .= "				default:\n";
			$this->salida .= "				if(x==true)\n";
			$this->salida .= "				{\n";
			$this->salida .= "					valores[cont]=valor\n";
			$this->salida .= "					cont++;\n";
			$this->salida .= "					if(cont==3)\n";
			$this->salida .= "					{\n";
			$this->salida .= "							for(i=0;i<document.formades$pfj.elements.length;i++)\n";
			$this->salida .= "							{\n";
			$this->salida .= "								if(document.formades$pfj.elements[i].type=='checkbox' && document.formades$pfj.elements[i].value!=valores[0]
																					&& document.formades$pfj.elements[i].value!=valores[1] && document.formades$pfj.elements[i].value!=valores[2])\n";
			$this->salida .= "								{\n";
			$this->salida .= "									document.formades$pfj.elements[i].disabled=true;\n";
			$this->salida .= "									document.formades$pfj.elements[i].checked=false;\n";
			$this->salida .= "								}\n";
			$this->salida .= "							}\n";
			$this->salida .= "					}\n";
			$this->salida .= "				}\n";
			$this->salida .= "				else\n";
			$this->salida .= "				{\n";
			$this->salida .= "				 j=0;\n";
			$this->salida .= "					for(var k=0;k<cont;k++)\n";
			$this->salida .= "						if(valores[k]!=valor)\n";
			$this->salida .= "							valores[j++]=valores[k];\n";
			$this->salida .= "					cont--;\n";
			$this->salida .= "					if(cont<3)\n";
			$this->salida .= "					{\n";
			$this->salida .= "							for(i=0;i<document.formades$pfj.elements.length;i++)\n";
			$this->salida .= "							{\n";
			$this->salida .= "								if(document.formades$pfj.elements[i].type=='checkbox')\n";
			$this->salida .= "								{\n";
			$this->salida .= "									document.formades$pfj.elements[i].disabled=false;\n";
			$this->salida .= "								}\n";
			$this->salida .= "							}\n";
			$this->salida .= "					}\n";
			$this->salida .= "				}\n";
			$this->salida .= "				break\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";

			$this->salida .= "		function mOvr(capa)";
			$this->salida .= "		{";
			$this->salida .= "			IniciaPro();";
			$this->salida .= "			e=xGetElementById(capa);";
			$this->salida .= "			e.style.display = \"\";";
			$this->salida .= "		}";
			$this->salida .= "		function mOut(capa)";
			$this->salida .= "		{";
			$this->salida .= "			e=xGetElementById(capa);";
			$this->salida .= "			e.style.display = \"none\";";
			$this->salida .= "		}";
			
			$this->salida .= "		function AccionCausa(forma,x)";
			$this->salida .= "		{";
			$this->salida .= "			forma.causa_cierre_caso$pfj.disabled=x;";
			$this->salida .= "		}";
			
			$this->salida .= "	</script>";
			
			$this->salida .= "<script language=\"javascript\">\n";

			$this->salida .= "	var capa_actual;\n";
			$this->salida .= "	function showhide1(Seccion)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		for(i=0; i<capas2.length; i++)\n";
			$this->salida .= "		{\n";
			$this->salida .= "			e = xGetElementById(capas2[i]);\n";
			$this->salida .= "			if(capas2[i] != Seccion)\n";
			$this->salida .= "			{\n";
			$this->salida .= "				e.style.display = \"none\";\n";
			$this->salida .= "			}\n";
			$this->salida .= "			else\n";
			$this->salida .= "			{\n";
			$this->salida .= "				if(e.style.display == \"none\")\n";
			$this->salida .= "				{\n";
			$this->salida .= "					e.style.display = \"\";\n";
			$this->salida .= "				}\n";
			$this->salida .= "				else \n";
			$this->salida .= "				{\n";
			$this->salida .= "					e.style.display = \"none\";\n";
			$this->salida .= "				}\n";
			$this->salida .= "			}\n";
			$this->salida .= "		}\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function funcion1(x)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var z=new Array(\"'\"+x[0]+\"'\",\"'\"+x[1]+\"'\");\n";
			$this->salida .= "		jsrsExecute('classes/modules/procesos1.php',VerDatos,'VerDatos',z);";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function VerDatos(x)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		document.getElementById('d2Contents').innerHTML=x; \n";
			$this->salida .= "		Iniciar('Consulta Examenes Clinicos');\n";
			$this->salida .= "		MostrarSpan('d2Container');\n";
			$this->salida .= "	}\n";

			$this->salida .= "	function AbrirVentana(url)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		window.open(url,'transcribir',\"width=700,height=0,x=2,y=2,resizable=no,status=no,scrollbars=yes,location=no\"); \n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function AbrirVentanaVer(url)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		window.open(url,'ver',\"width=710,height=0,x=2,y=2,resizable=no,status=no,scrollbars=yes,location=no\"); \n";
			$this->salida .= "	}\n";
			
			$this->salida .= "</script>\n";
			
			$this->salida .= "<script>\n";
			$this->salida .= "	var hiZ = 2;\n";
			$this->salida .= "	 var titulo = '';\n";
			$this->salida .= "	 var contenedor = '';\n";
			$this->salida .= "	 var capaActual = '';\n";
			$this->salida .= "	 var datos = new Array();\n";
			
			$this->salida .= "	function Iniciar(tit)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  titulo = 'titulo';\n";
			$this->salida .= "	  contenedor = 'd2Container';\n";
			$this->salida .= "		document.getElementById('titulo').innerHTML = '<center>'+tit+'</center>';\n";
			$this->salida .= "		ele = xGetElementById('d2Contents');\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/25, xScrollTop());\n";
			$this->salida .= "	  xResizeTo(ele,800,'auto');\n";
			$this->salida .= "		ele = xGetElementById(contenedor);\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/25, xScrollTop()+24);\n";
			$this->salida .= "	  xResizeTo(ele,800, 'auto');\n";
			$this->salida .= "		ele = xGetElementById(titulo);\n";
			$this->salida .= "	  xResizeTo(ele,780, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrar');\n";
			$this->salida .= "	  xResizeTo(ele,20, 20);\n";
			$this->salida .= "		xMoveTo(ele, 780, 0);\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function IniciaPro()\n";
			$this->salida .= "	{\n";
			$this->salida .= "		ele = xGetElementById('d2Container');\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth(), xScrollTop()+170);\n";
			$this->salida .= "	  xResizeTo(ele,250, 'auto');\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
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
			$this->salida .= "	function IniciarInterconsulta(tit)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  titulo = 'tituloI';\n";
			$this->salida .= "	  contenedor = 'ContainerI';\n";
			$this->salida .= "		document.getElementById(titulo).innerHTML = '<center>'+tit+'</center>';\n";
			$this->salida .= "		document.interconsulta.cantidad.value = '1';\n";
			$this->salida .= "		document.interconsulta.observacion.value = '';\n";
			$this->salida .= "		ele = xGetElementById(contenedor);\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop());\n";
			$this->salida .= "	  xResizeTo(ele,370, 'auto');\n";
			$this->salida .= "		ele = xGetElementById(titulo);\n";
			$this->salida .= "	  xResizeTo(ele,350, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrarI');\n";
			$this->salida .= "	  xResizeTo(ele,20, 20);\n";
			$this->salida .= "		xMoveTo(ele, 350, 0);\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function IniciarInterBusqueda(tit,capa,nombre,codigo)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		datos=new Array();";
			$this->salida .= "	  titulo = 'tituloB';\n";
			$this->salida .= "	  contenedor = 'ContainerB';\n";
			$this->salida .= "	  capaActual= capa;\n";
			$this->salida .= "	  datos[4] = ''+capa;\n";
			$this->salida .= "	  datos[5] = ''+nombre;\n";
			$this->salida .= "	  datos[6] = ''+codigo;\n";
			$this->salida .= "	  document.interconsultaB.busqueda.value=''\n";
			$this->salida .= "		document.getElementById(titulo).innerHTML = '<center>'+tit+'</center>';\n";
			$this->salida .= "		ele = xGetElementById(contenedor);\n";
			$this->salida .= "	  xMoveTo(ele, xClientWidth()/3, xScrollTop());\n";
			$this->salida .= "	  xResizeTo(ele,360, 'auto');\n";
			$this->salida .= "		ele = xGetElementById(titulo);\n";
			$this->salida .= "	  xResizeTo(ele,340, 20);\n";
			$this->salida .= "		xMoveTo(ele, 0, 0);\n";
			$this->salida .= "	  xEnableDrag(ele, myOnDragStart, myOnDrag, myOnDragEnd);\n";
			$this->salida .= "		ele = xGetElementById('cerrarB');\n";
			$this->salida .= "	  xResizeTo(ele,20, 20);\n";
			$this->salida .= "		xMoveTo(ele, 340, 0);\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function Obtener(vector)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  datos=vector;\n";
			$this->salida .= "	  capaActual=vector[4];\n";
			$this->salida .= "		jsrsExecute('classes/modules/InterCPN/Inter.php',TraerForma,'TraerForma',vector);";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function Evaluar(forma)\n";
			$this->salida .= "	{\n";
			$this->salida .= "	  mensaje='';\n";
			$this->salida .= "	  var canti= document.interconsulta.cantidad.value;\n";
			$this->salida .= "	  var obs = document.interconsulta.observacion.value;\n";
			$this->salida .= "		if( canti== '')\n";
			$this->salida .= "		{\n";
			$this->salida .= "			mensaje='DEBE INGRESAR UNA CANTIDAD';\n";
			$this->salida .= "		}\n";
			$this->salida .= "		if(obs == '')\n";
			$this->salida .= "		{\n";
			$this->salida .= "			mensaje='DEBE INGRESAR UNA OBSERVACION';\n";
			$this->salida .= "		}\n";
			$this->salida .= "		document.getElementById('errorI').innerHTML = '<center>'+mensaje+'</center>';\n";
			$this->salida .= "		if(mensaje=='')\n";
			$this->salida .= "		{\n";
			$this->salida .= "	  	datos[7]=canti;\n";
			$this->salida .= "	  	datos[8]=obs;\n";
			$this->salida .= "			SolicitudesInterconsulta(datos);\n";
			$this->salida .= "	  }\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function VInterconsultas(html)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		Cerrar('ContainerI');\n";
			$this->salida .= "		document.getElementById(capaActual).innerHTML = '<center>'+html+'</center>';\n";
			$this->salida .= "		document.getElementById('x'+capaActual).innerHTML = '';\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function TraerForma(html)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		document.getElementById('Interconsulta').innerHTML = html;";
			$this->salida .= "		IniciarInterconsulta(datos[1]);";
			$this->salida .= "		MostrarSpan('ContainerI');";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function BusquedaEsp(forma)\n";
			$this->salida .= "	{\n";
			$this->salida .= "		var resultado=jsrsArrayFromString(forma.busqueda.value,'ç')\n";
			$this->salida .= "		datos[0]=''+resultado[0];\n";
			$this->salida .= "		datos[1]=''+resultado[1];\n";
			$this->salida .= "		datos[2]=''+resultado[2];\n";
			$this->salida .= "		datos[3]='NULL';\n";
			$this->salida .= "		Cerrar('ContainerB')\n";
			$this->salida .= "		jsrsExecute('classes/modules/InterCPN/Inter.php',TraerForma,'TraerForma',datos);";
			$this->salida .= "	}\n";
			
			$this->salida .= "</script>\n";
			
			$this->salida .= "<div id='d2Container' class='d2Container' style=\"display:none\">\n";
			$this->salida .= "	<div id='titulo' class='draggable' style=\"	text-transform: uppercase;\"></div>\n";
			$this->salida .= "	<div id='cerrar' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('d2Container')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='d2Contents' class='d2Content'>\n";
			$this->salida .= "	</div>\n";
			$this->salida .= "</div>\n";

			$this->salida .= "<div id='ContainerI' class='d2Container' style=\"display:none\">\n";
			$this->salida .= "	<div id='tituloI' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
			$this->salida .= "	<div id='cerrarI' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContainerI')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='errorI' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
			$this->salida .= "		<form name=\"interconsulta\" action=\"\" method=\"post\">";
			$this->salida .= "			<div id='Interconsulta'>\n";
			$this->salida .= "			</div>\n";
			$this->salida .= "		</form>";
			$this->salida .= "</div>\n";

			$this->salida .= "<div id='ContainerB' class='d2Container' style=\"display:none\">\n";
			$this->salida .= "	<div id='tituloB' class='draggable' style=\"text-transform: uppercase;\"></div>\n";
			$this->salida .= "	<div id='cerrarB' class='draggable'><a class=\"hcPaciente\" href=\"javascript:Cerrar('ContainerB')\" title=\"Cerrar\" style=\"font-size:9px\">X</a></div><br><br>\n";
			$this->salida .= "	<div id='errorB' class='label_error' style=\"text-transform: uppercase;\"></div>\n";
			$this->salida .= "		<form name=\"interconsultaB\" action=\"\" method=\"post\">";
			$this->salida .= "			<div id='InterconsultaB'>\n";
			$this->salida .= "			<table border=\"0\" align=\"center\">\n";
			$this->salida .= "				<tr>\n";
			$this->salida .= "					<td class=\"modulo_table_list_title\" colspan=\"2\">ESPECIALIDADES</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "				<tr class=\"modulo_list_oscuro\">\n";
			$this->salida .= "					<td>\n";
			$this->salida .= "						SELECCIONE: <select name=\"busqueda\" class=\"select\">\n";
			$this->salida .= "						<option value=\"\">--SELECCIONE ESPECIALIDAD--</option>\n";
			for($i=0;$i<sizeof($especialidadesT);$i++)
				$this->salida .= "						<option value=\"".$especialidadesT[$i][especialidad]."ç".$especialidadesT[$i][descripcion]."ç".$especialidadesT[$i][cargo]."\">".$especialidadesT[$i][descripcion]."</option>\n";
			$this->salida .= "						</select>\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "					<td>\n";
			$this->salida .= "						<input type=\"button\" name=\"Aceptar\" value=\"ACEPTAR\" class=\"input-submit\" onclick=\"BusquedaEsp(this.form)\">\n";
			$this->salida .= "					</td>\n";
			$this->salida .= "				</tr>\n";
			$this->salida .= "			</table\n";
			$this->salida .= "			</div>\n";
			$this->salida .= "		</form>";
			$this->salida .= "</div>\n";

			return $this->salida;
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