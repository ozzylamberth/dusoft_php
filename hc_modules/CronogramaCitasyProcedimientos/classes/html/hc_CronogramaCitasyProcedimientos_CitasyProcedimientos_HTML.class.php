<?php
	/********************************************************************************* 
 	* $Id: hc_CronogramaCitasyProcedimientos_CitasyProcedimientos_HTML.class.php,v 1.2 2007/02/01 20:44:37 luis Exp $ 
 	*
 	* @copyright (C) 2005 IPSOFT-SA (www.ipsoft-sa-com)
 	* @package IPSOFT-SIIS
 	* 
	* @author    Luis Alejandro vargas. 
	* @package   hc_CronogramaCitasyProcedimientos
	* 
 	**********************************************************************************/

	class CitasyProcedimientos_HTML
	{

		function CitasyProcedimientos_HTML()
		{
			$this->redcolorf="#990000";
			return true;
		}
		
		function frmHistoria()
		{
			$this->salida="";
			return $this->salida;
		}
		
		function frmConsulta()
		{
			return true;
		}
		
		function frmCitasyProcedimientos($listaPro,$datosPro,$datosProcSolicitados,$semanas,$tp_semana,$semana_gestante,$fcp)
		{
			
			$pfj=SessionGetVar("Prefijo");
			$evolucion=SessionGetVar("Evolucion");
			$paso=SessionGetVar("Paso");
			$programa=SessionGetVar("Programa");

			$this->salida.= ThemeAbrirTablaSubModulo('CRONOGRAMA CITAS Y PROCEDIMIENTOS');
			
			if($this->ban==1)
			{
				$this->salida.= "      <table border=\"0\" width=\"100%\" align=\"center\">";
				$this->salida.= $this->SetStyle("MensajeError");
				$this->salida.= "      </table><br>";
				if($this->req==1)
					$_REQUEST=null;
			}
			
			$this->frmGestacion($semana_gestante,$fcp);
			
			$accion=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'CronogramaCitasyProcedimientos'));
			
			$num_sem=sizeof($semanas);
			
			$this->salida.="<form name=\"formades$pfj\" action=\"$accion\" method=\"post\">";
			$this->salida.="	<table align=\"center\" border=\"0\" width=\"100%\">";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td width=\"20%\">Rango Semanas</td>";
			for($i=0;$i<$num_sem;$i++)
			{
				$this->salida.="			<td width=\"9%\">".$semanas[$i][rango_inicio]."-".$semanas[$i][rango_fin]."</td>";
			}
			$this->salida.="			<td rowspan=\"3\">OBSERVACION</td>";
			$this->salida.="		</tr>";
			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td>Semanas de Gestacion</td>";
			for($i=0;$i<$num_sem;$i++)
			{
				$this->salida.="			<td>".$semanas[$i][rango_media]."</td>";
			}
			$this->salida.="		</tr>";

			$this->salida.="		<tr class=\"modulo_table_list_title\">";
			$this->salida.="			<td>Profesional</td>";
			for($i=0;$i<sizeof($semanas);$i++)
			{
				$tipo1=0;
				$tipo2=0;
				for($j=0;$j<sizeof($tp_semana);$j++)
				{
					if($tp_semana[$j][periodo_id]==($i+1))
					{
						if($tp_semana[$j][tipo_profesional]=='2')
						{
							$tipo1=2;
						}
						if($tp_semana[$j][tipo_profesional]=='3')
						{
							$tipo2=3;	
						}
					}
				}
				
				if($tipo1==2 && $tipo2!=3)				
				{
					$this->salida.="			<td>MED</td>";
				}
				else 
				if($tipo2==3 && $tipo1!=2)				
				{
					$this->salida.="			<td>ENF</td>";
				}
				else 
				if($tipo1==2 && $tipo2==3)
				{
					$this->salida .= "<td>MED&nbsp;&nbsp;ENF</td>";
				}
			}
			$this->salida.="		</tr>";
			
			for($n=0;$n<sizeof($semanas);$n++)
			{
				if($semanas[$n][rango_inicio]<=$semana_gestante AND $semanas[$n][rango_fin]>=$semana_gestante)
				{
					$periodo_id=$semanas[$n][periodo_id];
					$mp=$n;
					break;
				}
			}
			
			$h=0;
			for($i=0;$i<sizeof($listaPro);$i++)
			{
				if($h%2==0)
				{
					$estilo='hc_submodulo_list_claro';
				}
				else
				{
					$estilo='hc_submodulo_list_oscuro';
				}
				
				$this->salida.="	<tr class=\"$estilo\">";
				if(empty($listaPro[$i][alias]))
					$descripcion=$listaPro[$i][descripcion];
				else
					$descripcion=$listaPro[$i][alias];
				
				$this->salida.="	<td><label class=\"label\">".strtoupper($descripcion)."</label></td>";
				
				$s=0;
				$k=0;
        
				$cargo=0;
				for($j=0;$j<sizeof($datosPro);$j++)
				{
					if($listaPro[$i][cargo]==$datosPro[$j][cargo_cups])
					{
						if($datosPro[$j][periodo_metrica]==$mp)
						{
							$cargo[$j]=-1;
							$k=-1;
							break;
						}
            if($datosPro[$j][periodo_metrica]<$mp AND $j>=$k)
						{
							$k=$j;
						}
					}
				}
        
				$fin=$semanas[$mp][rango_fin];
				$pa=0;
				$entrar=0;
        for($n=0;$n<sizeof($semanas);$n++)
				{
					$ban=0;
					for($j=0;$j<sizeof($datosPro);$j++)
					{
						if($datosPro[$j][periodo_metrica]==$n and $listaPro[$i][cargo]==$datosPro[$j][cargo_cups])
						{
							if($semanas[$n][rango_inicio]<=$semana_gestante and $semanas[$n][rango_fin]>=$semana_gestante)
							{
								$x=0;
								for($m=0;$m<sizeof($datosProcSolicitados);$m++)
								{
									if($datosProcSolicitados[$m][periodo_sugerido]==$datosPro[$j][periodo_id] and $datosPro[$j][cargo_cups]==$datosProcSolicitados[$m][cargo_cups])
									{
										$this->salida.="	<td align=\"center\">";
										$this->salida.="	<label class=\"label\"><img src=\"".GetThemePath()."/images/checksi.png\"></label>";
										$this->salida.="	</td>";
										$x=1;
										$entrar=1;
										$pa=$datosProcSolicitados[$m][periodo_solicitud];
										break;
									}
								}
								if($x==0 AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
								{
									$this->salida.="	<input type=\"hidden\" name=\"periodo_sugerido$pfj\" value=\"".$datosPro[$j][periodo_id]."\">";
									$this->salida.="	<input type=\"hidden\" name=\"periodo_solicitado$pfj\" value=\"".$periodo_id."\">";
									$this->salida.="	<td align=\"center\">";
									$this->salida.="	<label class=\"label\"><input type=\"checkbox\" name=\"procedimientos".$pfj."[]\" value=\"".$listaPro[$i][cargo]."\"></label>";
									$this->salida.="	</td>";
								}
								else if($x==0 AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
								{
									$this->salida.="	<td align=\"center\">&nbsp;</td>";
								}
							}
							else
							{
								$all=0;
								for($m=0;$m<sizeof($datosProcSolicitados);$m++)
								{
									if($datosProcSolicitados[$m][periodo_sugerido]==$datosPro[$j][periodo_id] and $datosPro[$j][cargo_cups]==$datosProcSolicitados[$m][cargo_cups])
									{
										$this->salida.="	<td align=\"center\">";
										$this->salida.="	<label class=\"label\"><img src=\"".GetThemePath()."/images/checksi.png\"></label>";
										$this->salida.="	</td>";
										$all=1;
										$entrar=1;
										$pa=$datosProcSolicitados[$m][periodo_solicitud];
										break;
									}
								}
								if($all==0)
								{
									if($semanas[$n][rango_fin]>$fin AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
									{
										$this->salida.="	<td align=\"center\">";
										$this->salida.="	<label class=\"label\"><img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></label>";
										$this->salida.="	</td>";
									}
									else
									{
										if($cargo[$j]!=-1 AND $k==$j AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
										{
											$bandera=0;
											for($m=0;$m<sizeof($datosProcSolicitados);$m++)
											{
												if($datosProcSolicitados[$m][cargo_cups]==$listaPro[$i][cargo] AND $datosProcSolicitados[$m][periodo_solicitud]==$periodo_id)
												{
													$this->salida.="	<td align=\"center\" id=\"sol$i$n\">";
													$this->salida.="	<label class=\"label\"><img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></label>";
													$this->salida.="	</td>";
													$bandera=1;
													$entrar=1;
													$pa=$datosProcSolicitados[$m][periodo_solicitud];
													break;
												}
											}
											if($bandera==0)
											{
												$datos="new Array('".$listaPro[$i][cargo]."','".$datosPro[$j][periodo_id]."','$periodo_id')";
												$this->salida.="	<td align=\"center\" id=\"sol$i$n\">";
												$this->salida.="	<label class=\"label\"><a href=\"javascript:Ingreso($datos,'sol$i$n')\"><img src=\"".GetThemePath()."/images/checkno.png\" border=\"0\"></a></label>";
												$this->salida.="	</td>";
												$entrar=1;
												$pa=$periodo_id;
											}
										}
										else
										{
											$this->salida.="	<td align=\"center\">";	
											$this->salida.="	<label class=\"label\">&nbsp;</label>";	
											$this->salida.="	</td>";
										}
									}
								}
							}
							$ban=1;
							break;
						}
					}
					
					if($ban==0)
					{
						$flag=0;
						for($m=0;$m<sizeof($datosProcSolicitados);$m++)
						{
							for($j=0;$j<sizeof($datosPro);$j++)
							{
								if($datosProcSolicitados[$m][periodo_sugerido]==$datosPro[$j][periodo_id] and $datosPro[$j][cargo]==$datosProcSolicitados[$m][cargo_cups])
								{
									$this->salida.="	<td align=\"center\">";	
									$this->salida.="	<label class=\"label\"><img src=\"".GetThemePath()."/images/checksi.png\"></label>";	
									$this->salida.="	</td>";	
									$flag=1;
									$entrar=1;
									$pa=$datosProcSolicitados[$m][periodo_solicitud];
									break;
								}
							}
						}
						if($flag==0)
						{
							if(($entrar==0 OR ($entrar==1 AND $pa!=$periodo_id)) AND $semanas[$n][rango_inicio]<=$semana_gestante and $semanas[$n][rango_fin]>=$semana_gestante)
							{
								$band=0;
								for($m=0;$m<sizeof($datosProcSolicitados);$m++)
								{
									if($datosProcSolicitados[$m][cargo_cups]==$listaPro[$i][cargo] AND $datosProcSolicitados[$m][periodo_solicitud]==$periodo_id AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
									{
										$this->salida.="	<td align=\"center\">";
										$this->salida.="	<label class=\"label\"><img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></label>";
										$this->salida.="	</td>";
										$band=1;
										break;
									}
								}
								if($band==0 AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
								{
									$this->salida.="	<input type=\"hidden\" name=\"periodo_sugerido$pfj\" value=\"".$periodo_id."\">";
									$this->salida.="	<input type=\"hidden\" name=\"periodo_solicitado$pfj\" value=\"".$periodo_id."\">";
									$this->salida.="	<td align=\"center\">";
									$this->salida.="	<label class=\"label\"><input type=\"checkbox\" name=\"procedimientos".$pfj."[]\" value=\"".$listaPro[$i][cargo]."\"></label>";
									$this->salida.="	</td>";
								}
								elseif($band==0 AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
								{
									$this->salida.="<td align=\"center\">";	
									$this->salida.="	<label class=\"label\">&nbsp;</label>";
									$this->salida.="</td>";
								}
							}
							else
							{
								/*$band1=0;
								for($m=0;$m<sizeof($datosProcSolicitados);$m++)
								{
									if($datosProcSolicitados[$m][cargo_cups]==$listaPro[$i][cargo] AND $datosProcSolicitados[$m][periodo_sugerido]==$n+1 AND (!$_SESSION['cierre_caso'] AND $obj->cpn))
									{
										$this->salida.="	<td align=\"center\">";
										$this->salida.="	<label class=\"label\"><img src=\"".GetThemePath()."/images/checksi.png\" border=\"0\"></label>";
										$this->salida.="	</td>";
										$band1=1;
										break;
									}
								}
								if($band1==0)
								{*/
									$this->salida.="<td align=\"center\">";	
									$this->salida.="	<label class=\"label\">&nbsp;</label>";
									$this->salida.="</td>";
								//}
							}
						}
					}
				}
				$this->salida.="			<td>".$listaPro[$i][observacion]."</td>";	
				$this->salida.="	</tr>";
				$h++;
			}
			$this->salida.="	<tr class=\"hc_table_submodulo_list_title\">";
			$this->salida.="		<td> </td>";
			
			for($n=0;$n<sizeof($semanas);$n++)
			{
				if($semanas[$n][rango_inicio]<=$semana_gestante and $semanas[$n][rango_fin]>=$semana_gestante AND (!SessionGetVar("cierre_caso_$programa") AND SessionGetVar("cpn")))
				{
					$this->salida.="		<td><input type=\"submit\" name=\"solicitar$pfj\" value=\"SOLICITAR\" class=\"input-submit\"></td>";
				}
				else
				{
					$this->salida.="		<td>&nbsp;</td>";	
				}
			}
			
			$this->salida .= "<script>\n";
			$this->salida .= " var capaActual;\n";
			
			$this->salida .= "	function Ingreso(datos,capa1)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		capaActual=capa1;\n";
			$this->salida .= "		jsrsExecute('hc_modules/CronogramaCitasyProcedimientos/RemoteScripting/IngresoSolicitud.php',IngresoSolicitud,'IngresoSolicitud',datos);\n";
			$this->salida .= "	}\n";
			
			$this->salida .= "	function IngresoSolicitud(img)\n";
			$this->salida .= "	{ \n";
			$this->salida .= "		document.getElementById(capaActual).innerHTML = img;\n";
			$this->salida .= "	}\n";

			$this->salida .= "</script>\n";
			
			$this->salida.="	<input type=\"hidden\" name=\"items$pfj\" value=\"$k\">";
			$this->salida.="	<input type=\"hidden\" name=\"sol$pfj\" value=\"$c\">";
			$this->salida.="		<td> </td>";	
			$this->salida.="	</tr>";
			$this->salida.="	</table><br>";
			$this->salida.="</form>";
			
			$accion1=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'AyudasEducativas'));
			$accion2=ModuloHCGetURL($evolucion,$paso,0,'',false,array('accion'.$pfj=>'GraficasSeguimientoCPN'));
			$this->salida.="	<table align=\"center\" cellspacing=\"20\">";
			$this->salida.="	<tr>";
			$this->salida.="<form name=\"formasig$pfj\" action=\"$accion1\" method=\"post\">";
			$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"siguiente$pfj\" value=\"SIGUIENTE\"></td>";
			$this->salida.="</form>";
			$this->salida.="<form name=\"formavolver$pfj\" action=\"$accion2\" method=\"post\">";
			$this->salida.="   <td><input class=\"input-submit\" type=\"submit\" name=\"volver$pfj\" value=\"VOLVER\"></td>";
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
	}
?>